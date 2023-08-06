<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchStandingGateway
{
    public function findForSeasonClassAndDriver(
        UuidValueObject $season,
        NullableStringValueObject $seriesClass,
        UuidValueObject $standee,
        StandingType $standingType,
        PositiveIntValueObject $position,
    ): ?UuidValueObject;
}
