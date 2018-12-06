<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Tests\Unit\Formatter;

use Inpsyde\LogzIoMonolog\Formatter\LogzIoFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class LogzIoFormatterTest extends TestCase
{

    public function testConstruct()
    {
        $formatter = new LogzIoFormatter();
        static::assertEquals(JsonFormatter::BATCH_MODE_NEWLINES, $formatter->getBatchMode());
        $formatter = new LogzIoFormatter(JsonFormatter::BATCH_MODE_JSON);
        static::assertEquals(JsonFormatter::BATCH_MODE_JSON, $formatter->getBatchMode());
    }

    public function testFormat()
    {
        $formatter         = new LogzIoFormatter();
        $record            = $this->getRecord();
        $formatted_decoded = json_decode($formatter->format($record), true);

        static::assertArrayNotHasKey('datetime', $formatted_decoded);
        static::assertArrayHasKey('@timestamp', $formatted_decoded);
    }


    /**
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = array())
    {
        return array(
            'message'    => $message,
            'context'    => $context,
            'level'      => $level,
            'level_name' => Logger::getLevelName($level),
            'channel'    => 'test',
            'datetime'   => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra'      => array(),
        );
    }
}
