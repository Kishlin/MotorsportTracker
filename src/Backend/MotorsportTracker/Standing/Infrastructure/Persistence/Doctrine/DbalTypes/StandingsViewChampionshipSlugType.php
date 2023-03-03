<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class StandingsViewChampionshipSlugType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return StandingsViewChampionshipSlug::class;
    }
}
