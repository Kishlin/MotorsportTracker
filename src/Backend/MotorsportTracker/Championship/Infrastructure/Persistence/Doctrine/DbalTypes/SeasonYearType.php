<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\IntValueObjectType;

final class SeasonYearType extends IntValueObjectType
{
    protected function mappedClass(): string
    {
        return SeasonYear::class;
    }
}
