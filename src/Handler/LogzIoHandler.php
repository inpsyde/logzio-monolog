<?php

declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Handler;

use Inpsyde\LogzIoMonolog\Formatter\LogzIoFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl\Util;
use Monolog\Logger;

/**
 * @author Christian Brückner <chris@chrico.info>
 *
 * @link https://support.logz.io/hc/en-us/categories/201158705-Log-Shipping
 * @link https://app.logz.io/#/dashboard/data-sources/Bulk-HTTPS
 */
final class LogzIoHandler extends AbstractProcessingHandler
{
    public const HOST_EU = 'listener-eu.logz.io';
    public const HOST_US = 'listener.logz.io';

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @param string $token Log token supplied by Logz.io.
     * @param string $type Your log type - it helps classify the logs you send.
     * @param bool $ssl Whether or not SSL encryption should be used.
     * @param int|string $level The minimum logging level to trigger this handler.
     * @param bool $bubble Whether or not messages that are handled should bubble up the stack.
     * @param string $host One of existing listener hosts, by default 'listener.logz.io'
     *
     * @throws \LogicException If curl extension is not available.
     */
    public function __construct(
        string $token,
        string $type = 'http-bulk',
        bool $ssl = true,
        int $level = Logger::DEBUG,
        bool $bubble = true,
        string $host = self::HOST_US
    ) {

        $this->token = $token;
        $this->type = $type;

        $queryArgs = [
            'token' => $this->token,
            'type' => $this->type,
        ];

        $this->endpoint = $ssl
            ? 'https://' . $host . ':8071/'
            : 'http://' . $host . ':8070/';
        $this->endpoint .= '?' . http_build_query($queryArgs);

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
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
            static function (array $record) use ($level): bool {
                return ($record['level'] >= $level);
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
