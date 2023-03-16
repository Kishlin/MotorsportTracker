<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client;

final class ClientUsingCurl implements Client
{
    /**
     * {@inheritDoc}
     */
    public function fetch(string $url, array $headers = []): string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        curl_close($ch);

        assert(is_string($result));

        return $result;
    }
}
