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
            'formula-one' => 'Formula One',

            'fia-formula-2-championship' => 'FIA Formula 2 Championship',
            'fia-formula-3-championship' => 'FIA Formula 3 Championship',

            'formula-e' => 'Formula E',

            'f4-france' => 'F4 France',

            'w-series' => 'W Series',

            'world-endurance-championship' => 'World Endurance Championship',

            'gt-world-challenge-europe' => 'GT World Challenge Europe',

            'gt-world-challenge-europe-sprint-cup' => 'GT World Challenge Europe Sprint Cup',

            'gt4-france' => 'GT4 France',

            'imsa-sportscar-championship' => 'IMSA SportsCar Championship',

            'adac-gt-masters' => 'ADAC GT Masters',

            'motogp' => 'MotoGP',

            default => throw new UnhandledMatchError("Cannot find a match for {$slug}."),
        };
    }
}
