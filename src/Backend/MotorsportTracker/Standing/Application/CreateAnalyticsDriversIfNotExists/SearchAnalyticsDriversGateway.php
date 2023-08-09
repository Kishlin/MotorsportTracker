<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchAnalyticsDriversGateway
{
    public function find(UuidValueObject $season, UuidValueObject $driver): ?UuidValueObject;
}
