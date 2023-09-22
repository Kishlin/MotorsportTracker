<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application;

interface Connector
{
    /**
     * @param array<int|string> $parameters
     */
    public function fetch(string $url, array $parameters = []): string;
}
