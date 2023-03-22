<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchEntryGateway
{
    public function find(
        UuidValueObject $session,
        UuidValueObject $driver,
        UuidValueObject $team,
        PositiveIntValueObject $carNumber,
    ): ?UuidValueObject;
}
