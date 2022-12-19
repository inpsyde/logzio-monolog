<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Tests\Unit\Formatter;

use Inpsyde\LogzIoMonolog\Formatter\LogzIoFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

class LogzIoFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function testConstruct()
    {
        $formatter = new LogzIoFormatter();
        static::assertEquals(JsonFormatter::BATCH_MODE_NEWLINES, $formatter->getBatchMode());
        $formatter = new LogzIoFormatter(JsonFormatter::BATCH_MODE_JSON);
        static::assertEquals(JsonFormatter::BATCH_MODE_JSON, $formatter->getBatchMode());
    }

    /**
     * @test
     */
    public function testFormat()
    {
        $formatter         = new LogzIoFormatter();
        $record            = $this->getRecord();
        $normalizedRecord  = $formatter->normalizeRecord($record);

        static::assertArrayNotHasKey('datetime', $normalizedRecord);
        static::assertArrayHasKey('@timestamp', $normalizedRecord);
    }

    /**
     * @return LogRecord
     */
    protected function getRecord($level = Level::Warning, $message = 'test', $context = array())
    {
        return new LogRecord(
            \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'test',
            $level,
            $message,
            $context,
            [],
            null
        );
    }
}
