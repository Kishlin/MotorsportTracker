<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;

final class StandingsViewIdType extends AbstractUuidType
{
    protected function mappedClass(): string
    {
        return StandingsViewId::class;
    }
}
