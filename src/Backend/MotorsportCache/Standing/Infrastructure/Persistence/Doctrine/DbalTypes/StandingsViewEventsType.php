<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\ArrayValueObjectType;

final class StandingsViewEventsType extends ArrayValueObjectType
{
    protected function mappedClass(): string
    {
        return StandingsViewEvents::class;
    }
}