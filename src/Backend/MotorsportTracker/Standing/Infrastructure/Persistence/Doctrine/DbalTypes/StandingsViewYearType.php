<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class StandingsViewYearType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return StandingsViewYear::class;
    }
}
