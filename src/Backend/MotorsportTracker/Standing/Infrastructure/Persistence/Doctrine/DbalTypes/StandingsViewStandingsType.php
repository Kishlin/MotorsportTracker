<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractJsonType;

final class StandingsViewStandingsType extends AbstractJsonType
{
    protected function mappedClass(): string
    {
        return StandingsViewStandings::class;
    }
}
