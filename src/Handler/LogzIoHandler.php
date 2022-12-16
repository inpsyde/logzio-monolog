<?php

declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Handler;

use Inpsyde\LogzIoMonolog\Enum\Host;
use Inpsyde\LogzIoMonolog\Enum\Type;
use Inpsyde\LogzIoMonolog\Formatter\LogzIoFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl\Util;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @author Christian Brückner <chris@chrico.info>
 *
 * @link https://support.logz.io/hc/en-us/categories/201158705-Log-Shipping
 * @link https://app.logz.io/#/dashboard/data-sources/Bulk-HTTPS
 */
final class LogzIoHandler extends AbstractProcessingHandler
{
    private string $endpoint;

    /**
     * @param string $token Log token supplied by Logz.io.
     * @param string $type Your log type - it helps classify the logs you send.
     * @param bool $ssl Whether SSL encryption should be used.
     * @param Level $level The minimum logging level to trigger this handler.
     * @param bool $bubble Whether messages that are handled should bubble up the stack.
     * @param Host $host One of existing listener hosts, by default 'listener.logz.io'
     *
     * @throws \LogicException If curl extension is not available.
     */
    public function __construct(
        protected readonly string $token,
        string $type = 'http-bulk',
        bool $ssl = true,
        Level $level = Level::Debug,
        bool $bubble = true,
        Host $host = Host::UsEast1
    ) {
        $queryArgs = [
            'token' => $this->token,
            'type' => $type,
        ];

        $this->endpoint = $ssl
            ? 'https://' . $host->value . ':8071/'
            : 'http://' . $host->value . ':8070/';
        $this->endpoint .= '?' . http_build_query($queryArgs);

        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $this->send($record['formatted']);
    }

    // phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
    protected function send($data): void
    {
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $this->endpoint);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        Util::execute($handle);
    }

    public function handleBatch(array $records): void
    {
        $level = $this->level;
        $records = array_filter(
            $records,
            static function (LogRecord $record) use ($level): bool {
                return ($record->level >= $level);
            }
        );

        if ($records) {
            $this->send(
                $this->getFormatter()
                    ->formatBatch($records)
            );
        }
    }

    /**
     * {@inheritdoc}
     * phpcs:disable
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogzIoFormatter();
    }
}
