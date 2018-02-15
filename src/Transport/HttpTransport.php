<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Transport;

use Inpsyde\LogzIoMonolog\Client\ClientInterface;
use Inpsyde\LogzIoMonolog\Client\CurlClient;

class HttpTransport implements TransportInterface
{

    const SCHEME_HTTPS = 'https';
    const SCHEME_HTTP = 'http';

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $availableEndpoints = [
        self::SCHEME_HTTPS => 'https://listener.logz.io:8071/',
        self::SCHEME_HTTP  => 'http://listener.logz.io:8070/',
    ];

    /**
     * HttpTransport constructor.
     *
     * @param string $token
     * @param string $type
     * @param string $scheme
     */
    public function __construct(
        string $token,
        string $type = self::TYPE,
        string $scheme = self::SCHEME_HTTPS
    ) {

        if (!extension_loaded('curl')) {
            throw new \LogicException('The curl extension is needed to use the LogzIoHandler');
        }

        if (!isset($this->availableEndpoints[ $scheme ])) {
            $scheme = self::SCHEME_HTTPS;
        }

        $host           = $this->availableEndpoints[ $scheme ];
        $this->endpoint = $host . '?' . http_build_query(['token' => $token, 'type' => $type]);
    }

    public function send(string $data): bool
    {
        return $this->client()->write($data);
    }

    public function client(): ClientInterface
    {

        return new CurlClient($this->endpoint);
    }
}
