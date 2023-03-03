<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class TeamCountryIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return TeamCountryId::class;
    }
}
