<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsConstructorsIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchAnalyticsConstructorsGateway
{
    public function find(UuidValueObject $season, UuidValueObject $constructor): ?UuidValueObject;
}
