<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client;

interface Client
{
    /**
     * @param array<int, string> $headers
     */
    public function fetch(string $url, array $headers = []): string;
}
