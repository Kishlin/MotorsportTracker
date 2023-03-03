<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\JsonValueObjectType;

final class StandingsViewStandingsType extends JsonValueObjectType
{
    protected function mappedClass(): string
    {
        return StandingsViewStandings::class;
    }
}
