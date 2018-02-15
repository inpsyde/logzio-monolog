<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Client;

use Monolog\Handler\Curl\Util;

class CurlClient implements ClientInterface
{

    /**
     * @var string
     */
    protected $endpoint;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $data): bool
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $this->endpoint);
        curl_setopt($resource, CURLOPT_POST, true);
        curl_setopt($resource, CURLOPT_POSTFIELDS, $data);
        curl_setopt($resource, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        Util::execute($resource);

        return true;
    }
}
