<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum;

enum StandingType
{
    case DRIVER;

    case TEAM;

    case CONSTRUCTOR;

    public function toString(): string
    {
        return match ($this) {
            StandingType::TEAM        => 'team',
            StandingType::DRIVER      => 'driver',
            StandingType::CONSTRUCTOR => 'constructor',
        };
    }
}
