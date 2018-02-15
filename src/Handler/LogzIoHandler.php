<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Handler;

use Inpsyde\LogzIoMonolog\Formatter\LogzIoFormatter;
use Inpsyde\LogzIoMonolog\Transport\TransportInterface;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * @author Christian Brückner <chris@chrico.info>
 *
 * @see    https://support.logz.io/hc/en-us/categories/201158705-Log-Shipping
 * @see    https://app.logz.io/#/dashboard/data-sources/Bulk-HTTPS
 */
final class LogzIoHandler extends AbstractProcessingHandler
{

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @param TransportInterface $transport
     * @param int                $level  The minimum logging level to trigger this handler.
     * @param bool               $bubble Whether or not messages that are handled
     *                                   should bubble up the stack.
     *
     * @throws \LogicException If curl extension is not available.
     */
    public function __construct(
        TransportInterface $transport,
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {

        $this->transport = $transport;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $this->transport->send($record[ "formatted" ]);
    }

    public function handleBatch(array $records)
    {
        $level   = $this->level;
        $records = array_filter(
            $records,
            function (array $record) use ($level): bool {
                return ($record[ 'level' ] >= $level);
            }
        );

        if ($records) {
            $this->transport->send(
                $this->getFormatter()
                    ->formatBatch($records)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    // phpcs:disable InpsydeCodingStandard.CodeQuality.NoAccessors.NoGetter
    protected function getDefaultFormatter(): FormatterInterface
    {

        return new LogzIoFormatter();
    }
}
