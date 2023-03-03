<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class TeamStandingEventIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return TeamStandingEventId::class;
    }
}
