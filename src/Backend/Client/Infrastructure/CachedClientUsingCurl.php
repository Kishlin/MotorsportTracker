<?php

declare(strict_types=1);

namespace Kishlin\Backend\Client\Infrastructure;

use Kishlin\Backend\Client\Domain\Client;
use Psr\Log\LoggerInterface;

final readonly class CachedClientUsingCurl implements Client
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function fetch(string $url, array $headers = []): string
    {
        $this->logger->info(sprintf('GET %s', $url));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        curl_close($ch);

        assert(is_string($result));

        return $result;
    }

    public function post(string $url, array $headers = []): string
    {
        $this->logger->info(sprintf('POST %s', $url));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        curl_close($ch);

        assert(is_string($result));

        return $result;
    }
}
