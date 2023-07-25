<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Helper;

use UnhandledMatchError;

final class ChampionshipSlugHelper
{
    private function __construct()
    {
    }

    public static function unslugify(string $slug): string
    {
        return match ($slug) {
            'world-endurance-championship' => 'World Endurance Championship',

            'formula-one' => 'Formula One',

            default => throw new UnhandledMatchError("Cannot find a match for {$slug}."),
        };
    }
}
