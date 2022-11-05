<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractFloatType;

final class TeamStandingPointsType extends AbstractFloatType
{
    protected function mappedClass(): string
    {
        return TeamStandingPointsType::class;
    }
}
