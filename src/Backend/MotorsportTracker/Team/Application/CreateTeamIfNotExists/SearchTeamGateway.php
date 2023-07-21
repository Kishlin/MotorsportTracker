<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchTeamGateway
{
    public function findForSeasonNameAndRef(
        UuidValueObject $season,
        StringValueObject $name,
        NullableUuidValueObject $ref,
    ): ?UuidValueObject;
}
