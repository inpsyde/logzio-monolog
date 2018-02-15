<?php declare(strict_types=1);

namespace Inpsyde\LogzIoMonolog\Client;

use RuntimeException;

interface ClientInterface
{

    /**
     * Writes a given string.
     *
     * @param string $data
     *
     * @return bool
     *
     * @throws RuntimeException on write-failure
     */
    public function write(string $data): bool;
}
