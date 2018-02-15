<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Transport;

use Inpsyde\LogzIoMonolog\Client\ClientInterface;

interface TransportInterface
{

    /**
     * Type which is appended to the endpoint url.
     *
     * @link https://support.logz.io/hc/en-us/articles/210205985-Which-log-types-are-preconfigured-on-the-Logz-io-platform-
     *
     * @var string
     */
    const TYPE = 'http-bulk';

    public function send(string $data): bool;

    /**
     * Returns the client to send the data.
     *
     * @return ClientInterface
     */
    public function client(): ClientInterface;
}
