<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchAnalyticsTeamsGateway
{
    public function find(UuidValueObject $season, UuidValueObject $team): ?UuidValueObject;
}
