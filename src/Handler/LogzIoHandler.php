<?php declare( strict_types=1 );

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
     * @param string     $token  Log token supplied by Logz.io.
     * @param string     $type   Host name supplied by Logz.io.
     * @param bool       $ssl    Whether or not SSL encryption should be used.
     * @param int|string $level  The minimum logging level to trigger this handler.
     * @param bool       $bubble Whether or not messages that are handled should bubble up the stack.
     *
     * @throws \LogicException If curl extension is not available.
     */
    public function __construct(
        string $token,
        string $type = 'http-bulk',
        bool $ssl = true,
        $level = Logger::DEBUG,
        bool $bubble = true
    ) {

        $this->token = $token;
        $this->type = $type;
        $this->endpoint = $ssl
            ? 'https://listener.logz.io:8071/'
            : 'http://listener.logz.io:8070/';
        $this->endpoint .= '?'.http_build_query(
            [
                'token' => $this->token,
                'type' => $this->type,
            ]
        );

        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $this->send($record[ 'formatted' ]);
    }

    protected function send($data)
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->endpoint);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        Util::execute($handle);
    }

    public function handleBatch(array $records)
    {
        $level = $this->level;
        $records = array_filter(
            $records,
            function (array $record) use ($level): bool {

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
     */
    // phpcs:disable InpsydeCodingStandard.CodeQuality.NoAccessors.NoGetter
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogzIoFormatter();
    }
}
