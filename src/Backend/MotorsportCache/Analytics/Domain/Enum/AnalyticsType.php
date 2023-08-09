<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Domain\Enum;

enum AnalyticsType
{
    case DRIVERS;

    case TEAMS;

    case CONSTRUCTORS;

    public function toString(): string
    {
        return match ($this) {
            AnalyticsType::TEAMS        => 'teams',
            AnalyticsType::DRIVERS      => 'drivers',
            AnalyticsType::CONSTRUCTORS => 'constructors',
        };
    }
}
