<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\IntValueObjectType;

final class StandingsViewYearType extends IntValueObjectType
{
    protected function mappedClass(): string
    {
        return StandingsViewYear::class;
    }
}