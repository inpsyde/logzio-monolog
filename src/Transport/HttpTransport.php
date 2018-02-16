<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Transport;

class HttpTransport extends HttpsTransport implements TransportInterface
{

    /**
     * @var string
     */
    protected $endpoint = 'http://listener.logz.io:8070/';

}
