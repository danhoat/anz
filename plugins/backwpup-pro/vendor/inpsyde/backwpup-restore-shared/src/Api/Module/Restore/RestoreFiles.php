<?php

namespace Inpsyde\Restore\Api\Module\Restore;

use Inpsyde\Restore\AjaxHandler;
use Inpsyde\Restore\Api\Exception\FileSystemException;
use Inpsyde\Restore\Api\Module\Restore\Exception\RestorePathException;
use Inpsyde\Restore\Api\Module\Restore\Exception\ConfigFileException;
use Inpsyde\Restore\Api\Module\Restore\Exception\ConfigFileNotFoundException;
use Inpsyde\Restore\Api\Exception\ExceptionLinkHelper;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Inpsyde\Restore\Api\Module\Database\Import_Model;
use Inpsyde\Restore\Api\Module\Registry;

/**
 * Class RestoreFiles
 *
 * @package Inpsyde\Restore\Api\Module\Restore
 */
final class RestoreFiles
{

    private static $ignore_files_directories = [
        '.',
        '..',
        'manifest.json',
        'backwpup_readme.txt',
        'restore',
        'restore_temp',
    ];

    /**
     * Registry
     *
     * @var Registry
     */
    private $registry;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Translator
     *
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translation;

    /**
     * Archive Path Length
     *
     * It's used to remember the current archive path length.
     *
     * @var string The length of the archive path.
     */
    private $current_archive_extracted_path_length = '';

    /**
     * Context
     *
     * The context in which the instance operate. Default is `event_source` means
     * the instance is used in a EventSource request.
     *
     * @var string The context in which the instance operate.
     */
    private $context = AjaxHandler::EVENT_SOURCE_CONTEXT;

    /**
     * Restore constructor
     *
     * @param Registry $registry Registry instance.
     * @param LoggerInterface $logger Logger instance.
     * @param Translator $translation Translator instance.
     */
    public function __construct(
        Registry $registry,
        LoggerInterface $logger,
        TranslatorInterface $translation
    ) {

        $this->translation = $translation;
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * Restore Files
     *
     * @return int
     * @throws RestorePathException
     * @throws InvalidArgumentException
     */
    public function restore()
    {
        $errors = 0;

        do {
            // Ignore extra files.
            $extra_ignored_files = $this->registry->extra_files;
            $ignore = array_merge(self::$ignore_files_directories, $extra_ignored_files);

            // Set archive path and length used during copy files.
            $archive_extracted_path = $this->registry->extract_folder ?: '';
            $this->current_archive_extracted_path_length = strlen($archive_extracted_path);

            // The next directory to restore.
            $next_dir = $this->registry->next_dir_in_restore_list();
            $archive_extracted_path = $this->append_path($archive_extracted_path, $next_dir);

            // Create the path where the files must be restored.
            $restore_path = isset($this->registry->project_root) ? $this->registry->project_root : '';
            $restore_path = $this->append_path($restore_path, $next_dir);

            if (!$archive_extracted_path || !$restore_path) {
                throw new RestorePathException(
                    ExceptionLinkHelper::translateWithAppropiatedLink(
                        $this->translation,
                        sprintf(
                            $this->translation->trans(
                                'Archive Path and/or Restore Path is not set; Archive Path: %1$s; Restore Path: %2$s'
                            ),
                            $archive_extracted_path ?: '(empty string)',
                            $restore_path ?: '(empty string)'
                        ),
                        'ARCHIVE_RESTORE_PATH_CANNOT_BE_SET'
                    )
                );
            }

            $this->logger->info(
                sprintf(
                    $this->translation->trans('Restoring: %1$s'),
                    $archive_extracted_path
                )
            );

            try {
                // Copy all files that are within the archive extracted path.
                $this->copy_files($archive_extracted_path, $restore_path, $ignore);
            } catch (FileSystemException $exc) {
                $this->logger->error($exc->getMessage());
                ++$errors;
            }
        } while (!$this->file_restore_done());

        return $errors;
    }

    /**
     * Rewrite config file with new database credentials
     *
     * @throws ConfigFileNotFoundException
     * @throws FileSystemException
     * @throws ConfigFileException
     */
    public function rewriteConfig()
    {
        $configPath = $this->registry->extract_folder . DIRECTORY_SEPARATOR . 'wp-config.php';
        $this->logger->info(
            sprintf(
                $this->translation->trans('Attempting to rewrite config file at %s'),
                $configPath
            )
        );

        if (!file_exists($configPath)) {
            throw new ConfigFileNotFoundException(
                sprintf(
                    $this->translation->trans('Config file not found at %s'),
                    $configPath
                )
            );
        }
        if (!is_writable($configPath)) {
            throw new FileSystemException(
                sprintf(
                    $this->translation->trans('Config file not writable at %s'),
                    $configPath
                )
            );
        }

        // See if we can replace all credentials.
        $credentials = [
            'DB_NAME' => $this->registry->dbname,
            'DB_USER' => $this->registry->dbuser,
            'DB_PASSWORD' => $this->registry->dbpassword,
            'DB_HOST' => $this->registry->dbhost,
            'DB_CHARSET' => $this->registry->dbcharset,
        ];
        array_walk($credentials, function ($value, $key) {
            if ($key !== 'DB_CHARSET' && empty($value)) {
                throw new ConfigFileException(
                    sprintf(
                        $this->translation->trans('No value found for %s'),
                        $key
                    )
                );
            }
        });

        $config = file_get_contents($configPath);
        foreach ($credentials as $key => $value) {
            // It's OK if charset is not set.
            // But continue without replacing it in that case.
            if (empty($value)) {
                continue;
            }

            $config = preg_replace(
                '/(define\s*\(\s*([\'"])' . preg_quote($key) . '\2\s*,\s*([\'"])).*?(\3\s*\)\s*;)/i',
                '${1}' . $value . '${4}',
                $config,
                -1,
                $count
            );
            if ($count === 0) {
                throw new ConfigFileException(
                    sprintf(
                        $this->translation->trans('Could not replace value of %s'),
                        $key
                    )
                );
            }
        }

        file_put_contents($configPath, $config);

        $this->logger->info(
            $this->translation->trans('Config has been rewritten successfully.')
        );
    }

    /**
     * Append path
     *
     * @param string $path The path string.
     * @param string $next The path string.
     *
     * @return string The append path
     */
    private function append_path($path, $next)
    {
        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim(
            $next,
            DIRECTORY_SEPARATOR
        );
    }

    /**
     * Helper function determine type of given file.
     *
     * @param string $file File in source path.
     * @param array $ignore Array of files to ignore.
     *
     * @return string File type
     */
    private function file_type($file, $ignore)
    {
        if (in_array(basename($file), $ignore, true)) {
            return 'ignore';
        }

        if (is_link($file)) {
            return 'link';
        }

        if (is_dir($file)) {
            return 'dir';
        }

        if (is_file($file)) {
            return 'file';
        }

        return 'unknown';
    }

    /**
     * Check if more directories are available to restore.
     *
     * @return bool
     */
    private function file_restore_done()
    {
        // More to do if there are files left,
        // or if a file is currently being restored,
        // or if a file is being skipped
        if (count($this->registry->restore_list) > 0
            || $this->registry->has('restore_file_start_from')
            || $this->registry->has('restore_file_skip')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Handles actual copy operation of files to the restore path
     *
     * @param string $source
     * @param string $dest
     * @param array $ignore
     * @param bool $del
     * @param int $perm
     *
     * @return void
     * @throws FileSystemException
     */
    private function copy_files(
        $source,
        $dest,
        $ignore = ['.', '..'],
        $del = false,
        $perm = 0755
    ) {

        // Get files in directory
        $files = scandir($source);
        // If directory cannot be open, log and return.
        if ($files === false) {
            throw new FileSystemException(
                sprintf(
                    $this->translation->trans('The directory %1$s cannot be open. Skip this one.'),
                    $source
                )
            );
        }

        // 1. Create dir in destination if it does not exists
        if (!is_dir($dest)) {
            // phpcs:ignore
            mkdir($dest, $perm);
        }

        // phpcs:ignore
        foreach ($files as $file) {
            // Set the source file.
            $src_file = $this->append_path($source, $file);

            // Looking for the last file copied if the previous
            // request failed because of a time limit or something else.
            // The `restore_file_start_from` act as a control variable.
            if ($this->registry->has('restore_file_start_from')
                && $src_file !== $this->registry->restore_file_start_from
            ) {
                continue;
            }
            $this->registry->delete('restore_file_start_from');

            // Check if we have to skip a file that errored
            if ($this->registry->has('restore_file_skip')) {
                if ($src_file === $this->registry->restore_file_skip) {
                    $this->registry->delete('restore_file_skip');
                }
                continue;
            }

            switch ($this->file_type($src_file, $ignore)) {
                case 'file':
                    // Store the current file, in case we need it for the next request.
                    $this->registry->restore_file_start_from = $src_file;

                    // Set the destination.
                    $destinationAbsoluteFilePath = $this->append_path($dest, $file);

                    if (!is_writable($dest)) {
                        $this->registry->delete('restore_file_start_from');
                        throw new FileSystemException(
                            sprintf(
                                $this->translation->trans(
                                    'File %s cannot be restored because it is not writable or the directory doesn\'t have the right permissions'
                                ),
                                $destinationAbsoluteFilePath
                            )
                        );
                    }

                    // 2.a. Restore files in destination path
                    $result = @copy($src_file, $destinationAbsoluteFilePath);
                    // If restore wasn't performed, let's add a line of debug, so we can know which files cannot be copied.
                    if (!$result) {
                        // Skip this file on the next iteration
                        $this->registry->restore_file_skip = $src_file;
                        $this->registry->delete('restore_file_start_from');
                        throw new FileSystemException(
                            sprintf(
                                $this->translation->trans('Failed to restore file %1$s.'),
                                $src_file
                            )
                        );
                    }

                    if (AjaxHandler::EVENT_SOURCE_CONTEXT === $this->context) {
                        $this->echo_event_data(
                            'message',
                            [
                                'message' => $src_file,
                                'state' => 'progress',
                            ]
                        );
                    }

                    // phpcs:ignore
                    $del and unlink($src_file);

                    // If the file has been copied correctly, we can delete it.
                    $this->registry->delete('restore_file_start_from');
                    break;

                case 'dir':
                    // 2.b. Put subdirs in Registry::restore_list for next steps
                    $folder = substr($src_file, $this->current_archive_extracted_path_length);
                    $this->registry->add_to_restore_list($folder);
                    break;

                default:
                    break;
            }// endswitch
        }// endforeach
    }

    /**
     * Event Source Output
     *
     * @param string $event The type of the message. Message, Error, Log...
     * @param array $data The data to output.
     */
    private function echo_event_data($event, array $data)
    {
        echo "event: {$event}\n"; // phpcs:ignore
        echo 'data: ' . wp_json_encode($data) . "\n\n";
        flush();
    }
}
