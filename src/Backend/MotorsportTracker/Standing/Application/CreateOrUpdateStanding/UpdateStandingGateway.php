<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface UpdateStandingGateway
{
    public function update(
        UuidValueObject $record,
        StandingType $standingType,
        StrictlyPositiveIntValueObject $position,
        FloatValueObject $points,
    ): bool;
}