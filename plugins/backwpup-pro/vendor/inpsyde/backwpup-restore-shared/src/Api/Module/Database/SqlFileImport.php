<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Inpsyde\Restore\Api\Module\Database\Exception\DatabaseFileException;
use InvalidArgumentException;
use Symfony\Component\Translation\Translator;

class SqlFileImport implements ImportFileInterface
{
    /**
     * @var resource File handle
     */
    private $file_handle = false;

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    private $translation;

    /**
     * @var array read line cache
     */
    private $line_cache = [0 => ''];

    /**
     * The current delimiter.
     *
     * @var string
     */
    private $delimiter = ';';

    /**
     * SqlFileImport constructor.
     */
    public function __construct(Translator $translation)
    {
        $this->translation = $translation;
    }

    /**
     * {@inheritdoc}
     */
    public function set_import_file($file)
    {
        if (!is_file($file)) {
            throw new DatabaseFileException(
                sprintf($this->translation->trans('SQL file %1$s does not exist'), $file)
            );
        }

        if (!is_readable($file)) {
            throw new DatabaseFileException(
                sprintf($this->translation->trans('SQL file %1$s is not readable'), $file)
            );
        }

        // Close existing handle if open
        $this->close_file();

        $this->open_file($this->get_file_path($file));

        if ($this->is_file_open()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get_file_size()
    {
        $file_stats = fstat($this->get_file_handle());

        return $file_stats['size'];
    }

    /**
     * {@inheritdoc}
     */
    public function get_position()
    {
        if (!$this->is_file_open()) {
            throw new DatabaseFileException($this->translation->trans('Cannot get SQL file position'));
        }

        $pos = ftell($this->get_file_handle());

        return ['pos' => $pos, 'line_cache' => $this->line_cache];
    }

    /**
     * {@inheritdoc}
     */
    public function set_position(array $position)
    {
        if (!isset($position['pos'])) {
            throw new DatabaseFileException($this->translation->trans('SQL file position not set'));
        }

        if (isset($position['line_cache']) && is_array($position['line_cache'])) {
            $this->line_cache = $position['line_cache'];
        } else {
            throw new DatabaseFileException($this->translation->trans('SQL file line cache not set'));
        }

        if (!$this->is_file_open()) {
            throw new DatabaseFileException($this->translation->trans('SQL file is not open'));
        }

        $result = fseek($this->file_handle, $position['pos']);
        if ($result === -1) {
            throw new DatabaseFileException($this->translation->trans('Cannot set SQL file position'));
        }

        return true;
    }

    /**
     * Get the current query delimiter.
     *
     * Defaults to ; but can be set with the DELIMITER keyword.
     *
     * @return string The current delimiter
     */
    protected function get_delimiter()
    {
        return $this->delimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function get_query()
    {
        $query = '';
        $line = '';

        while ($line !== false) {
            $line = $this->get_line_from_file();
            if (!$line) {
                continue;
            }
            if (substr($line, 0, 2) === '--') {
                continue;
            }
            if (substr($line, 0, 1) === '#') {
                continue;
            }
            if (preg_match('/^DELIMITER\s+([^ ]+)/i', $line, $matches) === 1) {
                $this->delimiter = trim($matches[1]);
                continue;
            }

            $query .= $line;
            $delimiterLength = strlen($this->get_delimiter());
            if (preg_match(
                '/' . preg_quote($this->get_delimiter(), '/') . '\s*$/',
                $query
            ) === 1) {
                $query = substr(rtrim($query), 0, -$delimiterLength) . ';';
                break;
            } else {
                $query .= "\n";
            }
        }

        return $query;
    }

    /**
     * Test is file a sql file.
     *
     * @param string $file
     *
     * @return bool
     */
    public function is_sql_file($file)
    {
        if (!is_file($file)) {
            return false;
        }

        try {
            $content = file_get_contents($this->get_file_path($file), false, null, 0, 1024 * 1024);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        if (stristr($content, 'INSERT') === false || stristr($content, 'CREATE TABLE') === false) {
            return false;
        }

        return true;
    }

    /**
     * Clean up.
     */
    public function __destruct()
    {
        $this->close_file();
    }

    /**
     * Read sql query from file query by query.
     *
     * @return string line
     */
    protected function get_line_from_file()
    {
        if (!$this->is_file_open()) {
            throw new DatabaseFileException($this->translation->trans('SQL file is not open'));
        }

        if (!$this->line_cache) {
            return false;
        }

        if (count($this->line_cache) === 1) {
            $file_content = '';
            do {
                $file_content .= fread($this->file_handle, 8192);
            } while ($file_content !== '' && strpos($file_content, "\n") === false);

            $file_lines = explode("\n", $file_content);

            if ($this->line_cache) {
                $file_lines[0] = array_shift($this->line_cache) . $file_lines[0];
            }

            $this->line_cache = $file_lines;
        }

        return array_shift($this->line_cache);
    }

    /**
     * Get the file handle.
     *
     * @return string The file handle
     */
    protected function get_file_handle()
    {
        return $this->file_handle;
    }

    protected function get_file_path($file)
    {
        if (preg_match('/\.sql\.gz$/i', $file) === 1) {
            return 'compress.zlib://' . $file;
        } elseif (preg_match('/\.sql\.bz2$/i', $file) === 1) {
            return 'compress.bzip2://' . $file;
        } elseif (preg_match('/\.sql$/i', $file) === 1) {
            return $file;
        } elseif (preg_match('/^[^.]+$/', $file) === 1) {
            throw new InvalidArgumentException(
                $this->translation->trans('Missing SQL file extension')
            );
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    $this->translation->trans('Invalid SQL file extension .%1$s'),
                    pathinfo($file, PATHINFO_EXTENSION)
                )
            );
        }
    }

    /**
     * Open the file for reading.
     *
     * @param string $filename The file to open
     */
    protected function open_file($filename)
    {
        $this->file_handle = fopen($filename, 'rb');
    }

    /**
     * Check if the file is open.
     *
     * @return bool true if the file is open, false otherwise
     */
    protected function is_file_open()
    {
        return is_resource($this->get_file_handle());
    }

    /**
     * Closes the file handle.
     */
    protected function close_file()
    {
        if ($this->is_file_open()) {
            fclose($this->get_file_handle());
        }
    }
}
