<?php

declare(strict_types=1);

namespace Kishlin\Backend\Client\Domain;

interface Client
{
    /**
     * @param array<int, string> $headers
     */
    public function fetch(string $url, array $headers = []): string;

    /**
     * @param array<int, string> $headers
     */
    public function post(string $url, array $headers = []): string;
}
