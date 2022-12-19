<?php

declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

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
    public const DATETIME_FORMAT = 'c';

    /**
     * Overrides the default batch mode to new lines for compatibility with the Logz.io bulk API.
     *
     * @param int $batchMode
     * @param bool $appendNewline
     */
    public function __construct(int $batchMode = self::BATCH_MODE_NEWLINES, bool $appendNewline = true)
    {
        parent::__construct($batchMode, $appendNewline);
    }

    /**
     * Appends the '@timestamp' parameter for Logz.io.
     *
     * @see \Monolog\Formatter\JsonFormatter::format()
     * @see https://support.logz.io/hc/en-us/articles/210206885
     */
    public function normalizeRecord(LogRecord $record): array
    {
        $recordData = parent::normalizeRecord($record);

        if (isset($recordData['datetime'])) {
            $recordData['@timestamp'] = (new \DateTimeImmutable($recordData['datetime']))->format(self::DATETIME_FORMAT);

            unset($recordData['datetime']);
        }

        // Logz.io does not allow [null] or [""] as context/extra.
        if (isset($recordData['context'])) {
            $recordData['context'] = array_filter((array) $recordData['context']);
        }

        if (isset($recordData['extra'])) {
            $recordData['extra'] = array_filter((array) $recordData['extra']);
        }

        return $recordData;
    }
}
