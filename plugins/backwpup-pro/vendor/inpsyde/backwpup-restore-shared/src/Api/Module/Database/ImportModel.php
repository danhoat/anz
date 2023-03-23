<?php
/**
 * Import Model
 *
 * @since   1.0.0
 * @package Inpsyde\Restore\Api\Module\Database
 */

namespace Inpsyde\Restore\Api\Module\Database;

use Exception;
use Inpsyde\Restore\Api\Module\Database\Exception\SqlException;
use Inpsyde\Restore\AjaxHandler;
use Inpsyde\Restore\Api\Module\ImportInterface;
use Inpsyde\Restore\Api\Module\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImportModel
 *
 * @since   1.0.0
 * @package Inpsyde\Restore\Api\Module\Database
 */
final class ImportModel implements ImportInterface
{

    /**
     * Factory Database Type
     *
     * @var DatabaseTypeFactory
     */
    private $db_connection;

    /**
     * Factory Importer File
     *
     * @var ImportFileFactory
     */
    private $file_import;

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
     * @var TranslatorInterface The translator to use to translate the strings.
     */
    private $translator;

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
     * Number of replacements made in database
     *
     * @var integer
     */
    private $replacements;

    /**
     * Import_Model constructor.
     *
     * @param DatabaseTypeFactory $db_connection
     * @param ImportFileFactory $file_import
     * @param Registry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(
        DatabaseTypeFactory $db_connection,
        ImportFileFactory $file_import,
        Registry $registry,
        LoggerInterface $logger,
        TranslatorInterface $translator
    ) {

        $this->db_connection = $db_connection;
        $this->file_import = $file_import;
        $this->registry = $registry;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->replacements = 0;
    }

    /**
     * @inheritdoc
     */
    public function import()
    {
        $errors = 0;
        $database = $this->db_connection->database_type();
        $database->connect();

        // Set sql_mode to prevent strict error on zero date
        $database->query("SET sql_mode = ''");
        // Prevent issues when dropping a table that has a foreign key.
        $database->query('SET FOREIGN_KEY_CHECKS = 0');

        $file = $this->file_import->import_file('sql');
        $file->set_import_file($this->registry->dbdumpfile);

        // Save file size to calculate percentage of file process for output
        $this->registry->dbdumpsize = $file->get_file_size();

        if (isset($this->registry->dbdumppos)) {
            $file->set_position($this->registry->dbdumppos);
        } else {
            $this->registry->dbdumppos = [];
        }

        $query = $file->get_query();

        while (!empty($query)) {
            try {
                $query = $this->replace($query, $database);
                $response = $database->query($query);
            } catch (Exception $exc) {
                $this->logger->error($exc->getMessage());
                ++$errors;
            }

            // Only for event source calls and only when rows are stored into the db.
            if ($response && AjaxHandler::EVENT_SOURCE_CONTEXT === $this->context) {
                preg_match('/`(\w+)`/i', $query, $match);
                $this->echo_event_data(
                    'message',
                    [
                        'message' => isset($match[1]) ? $match[1] : '',
                        'state' => 'progress',
                    ]
                );
            }

            // Update the restoring progress.
            $this->registry->dbdumppos = $file->get_position();
            $this->save_progress();

            $query = $file->get_query();
        }

        $database->query('SET FOREIGN_KEY_CHECKS = 1');

        // After we have finished to import, let's update one last time the progress.
        // We cannot get the 100% in the log because may be the latest query return a value valuable as false,
        // and the progress is not saved the last time.
        $this->registry->dbdumppos = $file->get_position();
        $this->registry->finish_job('db_restore');
        $this->save_progress();

        // Clean.
        unset(
            $import_file,
            $database,
            $query
        );

        // Report replacements made
        if ($this->replacements > 0) {
            $this->logger->info(
                sprintf(
                    $this->translator->trans('Replacements made: %1$d'),
                    $this->replacements
                )
            );

            // Reset replacements in case there are more queries
            $this->replacements = 0;
        }

        if (AjaxHandler::EVENT_SOURCE_CONTEXT === $this->context) {
            $message = $errors
                ? $this->translator->trans('Database restored with errors.')
                : $this->translator->trans('Database restored successfully.');

            $this->echo_event_data(
                'message',
                [
                    'message' => $message,
                    'state' => 'done',
                ]
            );
        }
    }

    /**
     * Perform replacements in query
     *
     * @param string $query The database query to search and replace
     * @param \Inpsyde\Restore\Api\Module\Database\DatabaseInterface $database The database connection
     *
     * @return string The replaced query
     */
    private function replace($query, DatabaseInterface $database)
    {
        if (!$this->registry->old_url || !$this->registry->new_url) {
            return $query;
        }

        // Only operate on INSERT queries
        if (strtoupper(substr($query, 0, 6)) !== 'INSERT') {
            return $query;
        }

        // Cache the old query in case there is an error
        $old_query = $query;

        // Attempt to parse the query
        try {
            $matched = preg_match(
                '/^INSERT\s+INTO\s+.*\((?<fields>[^)]+)\)\s+VALUES\s*(?<values>.+)/is',
                $query,
                $matches,
                PREG_OFFSET_CAPTURE
            );

            if ($matched !== 1) {
                throw new SqlException();
            }

            $fields = preg_split('/,\s*/', $matches['fields'][0]);
            $fields = array_map(function ($field) {
                // Allow hierarchical fields
                $parts = explode('.', $field);
                return strtolower(trim(array_pop($parts), '`'));
            }, $fields);

            if (empty($fields)) {
                throw new SqlException();
            }

            $position = $matches['values'][1];

            // Tokenize and process until query is parsed
            while ($position < strlen($query)) {
                $query = $this->processNextToken($query, $position, $fields, $database);
            }
        } catch (SqlException $e) {
            $this->logger->warning(
                sprintf(
                    $this->translator->trans(
                        'Malformed query encountered; cannot perform replace. Query: %s'
                    ),
                    $old_query
                )
            );

            // Always return original
            return $old_query;
        }

        return $query;
    }

    /**
     * Process next token in query
     *
     * @param string $query The entire query being parsed
     * @param int $position The current position of parsing
     * @param array $fields The array of fields for this query
     * @param \Inpsyde\Restore\Api\Module\Database\DatabaseInterface $database The database connection
     *
     * @return string The query parsed at the next token
     * @throws SqlException If query is malformed
     */
    private function processNextToken(
        $query,
        &$position,
        array &$fields,
        DatabaseInterface $database
    ) {

        if (key($fields) === 0 && $query[$position] === '(') {
            $position++;
        }

        $query = $this->processField($query, $position, $fields, $database);

// If next character is a comma, then we are moving to the next field
        if ($query[$position] === ',') {
            preg_match('/,\s*(?<remaining>.+)/sA', $query, $matches, PREG_OFFSET_CAPTURE, $position);
            $position = $matches['remaining'][1];
            next($fields);
        } elseif ($query[$position] === ')') {
            // End of record
            $position++;

            // If comma, we have another record
            if ($query[$position] === ',') {
                preg_match('/,\s*(?<remaining>.+)/sA', $query, $matches, PREG_OFFSET_CAPTURE, $position);
                $position = $matches['remaining'][1];
                reset($fields);
            } elseif ($query[$position] === ';') {
                $position++;
            }
        }

        return $query;
    }

    /**
     * Process the next field
     *
     * @param string $query The entire query being parsed
     * @param int $position The current position of parsing
     * @param array $fields The array of fields for this query
     * @param \Inpsyde\Restore\Api\Module\Database\DatabaseInterface $database The database connection
     *
     * @return string The query parsed at the next field
     * @throws SqlException If query is malformed
     */
    private function processField(
        $query,
        &$position,
        array $fields,
        DatabaseInterface $database
    ) {

        if (preg_match('/[^\'",)]+(?=,\s*|\))(?<remaining>.+)/sA', $query, $matches, PREG_OFFSET_CAPTURE, $position)) {
            // Non-string parameter, which we don't care about
            $position = $matches['remaining'][1];
        } elseif (preg_match('/(?<value>([\'"]).?\2)(?=,\s*|\))(?<remaining>.+)/sA', $query, $matches, PREG_OFFSET_CAPTURE, $position)) {
            // One or zero characters in string value
            $position = $matches['remaining'][1];
        } elseif (preg_match('/(?<value>([\'"]).+?[^\\\]\2)(?=,\s*|\))(?<remaining>.+)/sA', $query, $matches, 0, $position)) {
            $value = $matches['value'];

            if (current($fields) === 'guid') {
                // Skip any guid field
                $position += strlen($value);
            } else {
                $new_value = $this->replaceValue(
                    $value,
                    $database
                );

                $query = substr_replace(
                    $query,
                    $new_value,
                    $position,
                    strlen($value)
                );

                $position += strlen($new_value);
            }
        } else {
            // Does not match, so error
            throw new SqlException();
        }

        return $query;
    }

    /**
     * Perform replacement for migration on a specific value
     *
     * @param string $value The value to search and replace
     * @param \Inpsyde\Restore\Api\Module\Database\DatabaseInterface $database The database connection
     *
     * @return string The replaced value
     */
    private function replaceValue($value, DatabaseInterface $database)
    {
        static $esc_old_url, $esc_new_url;
        if (!isset($esc_old_url)) {
            $esc_old_url = $database->escape($this->registry->old_url);
        }
        if (!isset($esc_new_url)) {
            $esc_new_url = $database->escape($this->registry->new_url);
        }

        // Fix serialized data first
        // It should be preceded by either ' or " (beginning of value in DB),
        // ; (from end of previous serialized value),
        // or { (from beginning of serialized array).
        // It should consist of: s:<length>:\"<value>\";
        // It should be followed by ' or " (end of value in DB),
        // } (end of serialized array),
        // or character denoting beginning of another serialized value.
        preg_match_all(
            '/(?<=\'|"|;|\{)(?<serialized>s:(?<length>\d+):\\\"(?<value>.+?)\\\";)(?=\'|"|\}|(?:s|i|d|b|a|O):)/',
            $value,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            // Only replace if the old URL is contained within the value
            if (strpos($match['value'], $esc_old_url) === false) {
                continue;
            }

            $replaced = str_replace($esc_old_url, $esc_new_url, $match['value'], $count);
            $length = $match['length']
                + ((strlen($this->registry->new_url) - strlen($this->registry->old_url))) * $count;
            $value = str_replace(
                $match['serialized'],
                "s:$length:\\\"$replaced\\\";",
                $value
            );
            $this->replacements += $count;
        }

        $value = str_replace(
            $esc_old_url,
            $esc_new_url,
            $value,
            $count
        );
        $this->replacements += $count;

        return $value;
    }

    /**
     * Save Restore Progress
     *
     * Helper method for calculating the progress of DB file restore and saving it to registry.
     */
    private function save_progress()
    {
        $progress = (int)floor(($this->registry->dbdumppos['pos'] / $this->registry->dbdumpsize) * 100);

        // Log valid progress and not log the same value multiple times.
        if (0 < $progress && $progress > $this->registry->migration_progress) {
            $this->registry->update_progress($progress);
            $this->logger->info(
                sprintf(
                    'SQL File restore: dbdumpos [%d], dbdumpsize [%d], progress [%d]',
                    $this->registry->dbdumppos['pos'],
                    $this->registry->dbdumpsize,
                    $progress
                )
            );
        }
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
