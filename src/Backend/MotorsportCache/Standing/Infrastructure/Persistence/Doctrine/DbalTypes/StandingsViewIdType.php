<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class StandingsViewIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return StandingsViewId::class;
    }
}
