<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchAnalyticsGateway
{
    public function find(UuidValueObject $season, UuidValueObject $driver): ?UuidValueObject;
}
