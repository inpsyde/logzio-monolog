<?php

declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Formatter;

use Monolog\Formatter\JsonFormatter;

/**
 * Encodes message information into JSON in a format compatible with Logz.io.
 *
 * @author Christian Brückner <chris@chrico.info>
 */
class LogzIoFormatter extends JsonFormatter
{
    /**
     * yyyy-MM-dd'T'HH:mm:ss.SSSZ
     */
    const DATETIME_FORMAT = 'c';

    /**
     * Overrides the default batch mode to new lines for compatibility with the Logz.io bulk API.
     *
     * @param int  $batchMode
     * @param bool $appendNewline
     */
    public function __construct(int $batchMode = self::BATCH_MODE_NEWLINES, bool $appendNewline = true)
    {
        parent::__construct($batchMode, $appendNewline);
    }

    /**
     * Appends the '@timestamp' parameter for Logz.io.
     *
     * @param array $record
     *
     * @link https://support.logz.io/hc/en-us/articles/210206885
     * @see \Monolog\Formatter\JsonFormatter::format()
     *
     * @return string
     */
    public function format(array $record): string
    {
        if (isset($record['datetime']) && ( $record['datetime'] instanceof \DateTimeInterface )) {
            $record['@timestamp'] = $record['datetime']->format(self::DATETIME_FORMAT);
            unset($record['datetime']);
        }

        // Logz.io does not allow [null] or [""] as context/extra.
        if (isset($record['context'])) {
            $record['context'] = array_filter((array)$record['context']);
        }

        if (isset($record['extra'])) {
            $record['extra'] = array_filter((array)$record['extra']);
        }

        return parent::format($record);
    }
}
