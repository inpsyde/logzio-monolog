<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Transport;

use Inpsyde\LogzIoMonolog\Client\ClientInterface;
use Inpsyde\LogzIoMonolog\Client\CurlClient;

class HttpsTransport implements TransportInterface
{

    /**
     * @var string
     */
    protected $endpoint = 'https://listener.logz.io:8071/';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * HttpsTransport constructor.
     *
     * @param string $token
     * @param string $type
     */
    public function __construct(string $token, string $type = self::TYPE)
    {

        $this->endpoint = $this->endpoint . '?' . http_build_query(['token' => $token, 'type' => $type]);
    }

    public function send(string $data): bool
    {
        return $this->client()->write($data);
    }

    public function client(): ClientInterface
    {

        if ($this->client === null) {
            $this->client = new CurlClient($this->endpoint);
        }

        return $this->client;
    }
}
