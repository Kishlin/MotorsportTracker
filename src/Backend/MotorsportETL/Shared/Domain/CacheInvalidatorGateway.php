<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain;

interface CacheInvalidatorGateway
{
    public function invalidate(string $table, string $key): void;
}
