<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client;

interface Client
{
    public function fetch(string $url): string;
}
