<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Standing\Command\PerSeason;

final class AddFormulaOne2022StandingsCommand extends AbstractPerSeasonStandingsCommand
{
    protected function name(): string
    {
        return 'kishlin:motorsport:standing:per-season:formula1-2022';
    }

    protected function championship(): string
    {
        return 'formula1';
    }

    protected function year(): int
    {
        return 2022;
    }

    /**
     * @return string[]
     */
    protected function events(): array
    {
        return ['bh', 'sa', 'au', 'it', 'us', 'es', 'mc', 'az', 'ca', 'gb', 'at', 'fr', 'hu', 'be', 'nl', 'it', 'sg', 'jp', 'us', 'mx', 'br', 'ae'];
    }

    /**
     * @return array<int, array{driver: string, points: int[], team: string, color: string}>
     */
    protected function results(): array
    {
        return [
            [
                'driver' => 'Max Verstappen',
                'team'   => 'Red Bull Racing',
                'points' => [0, 25, 0, 34, 26, 25, 15, 25, 25, 6, 27, 25, 25, 26, 26, 25, 6, 25, 25, 25, 13, 25],
                'color'  => '#0022ff',
            ],
            [
                'driver' => 'Charles Leclerc',
                'team'   => 'Ferrari',
                'points' => [26, 19, 26, 15, 18, 0, 12, 0, 10, 12, 32, 0, 8, 8, 15, 18, 18, 15, 15, 8, 15, 18],
                'color'  => '#ff0000',
            ],
            [
                'driver' => 'Sergio Perez',
                'team'   => 'Red Bull Racing',
                'points' => [0, 12, 18, 24, 12, 19, 25, 19, 0, 18, 4, 12, 10, 18, 10, 9, 25, 18, 12, 15, 10, 15],
                'color'  => '#0022ff',
            ],
            [
                'driver' => 'George Russell',
                'team'   => 'Mercedes',
                'points' => [12, 10, 15, 12, 10, 15, 10, 15, 12, 0, 17, 15, 15, 12, 18, 15, 0, 4, 11, 13, 34, 10],
                'color'  => '#a8a8a8',
            ],
            [
                'driver' => 'Carlos Sainz',
                'team'   => 'Ferrari',
                'points' => [18, 15, 0, 5, 15, 12, 18, 0, 19, 25, 6, 11, 12, 15, 4, 12, 15, 0, 0, 10, 22, 12],
                'color'  => '#ff0000',
            ],
            [
                'driver' => 'Lewis Hamilton',
                'team'   => 'Mercedes',
                'points' => [15, 1, 12, 0, 8, 10, 4, 12, 15, 16, 16, 18, 19, 0, 12, 10, 2, 10, 18, 18, 24, 0],
                'color'  => '#a8a8a8',
            ],
            [
                'driver' => 'Lando Norris',
                'team'   => 'McLaren',
                'points' => [0, 6, 10, 19, 0, 4, 9, 2, 0, 8, 6, 6, 6, 0, 6, 6, 12, 1, 8, 2, 2, 9],
                'color'  => '#ff7400',
            ],
            [
                'driver' => 'Esteban Ocon',
                'team'   => 'Alpine',
                'points' => [6, 8, 6, 0, 4, 6, 0, 1, 8, 0, 13, 4, 2, 6, 2, 0, 0, 12, 0, 4, 4, 6],
                'color'  => '#03f4ff',
            ],
            [
                'driver' => 'Fernando Alonso',
                'team'   => 'Alpine',
                'points' => [2, 0, 0, 0, 0, 2, 6, 6, 2, 10, 1, 8, 4, 10, 8, 0, 0, 6, 6, 0, 10, 0],
                'color'  => '#03f4ff',
            ],
            [
                'driver' => 'Valtteri Bottas',
                'team'   => 'Alfa Romeo',
                'points' => [8, 0, 4, 12, 6, 8, 2, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2, 0],
                'color'  => '#7a1423',
            ],
            [
                'driver' => 'Daniel Ricciardo',
                'team'   => 'McLaren',
                'points' => [0, 0, 8, 3, 0, 0, 0, 4, 0, 0, 2, 2, 0, 0, 0, 0, 10, 0, 0, 6, 0, 2],
                'color'  => '#ff7400',
            ],
            [
                'driver' => 'Sebastian Vettel',
                'team'   => 'Aston Martin Racing',
                'points' => [0, 0, 0, 4, 0, 0, 1, 8, 0, 2, 0, 0, 1, 4, 0, 0, 4, 8, 4, 0, 0, 1],
                'color'  => '#208624',
            ],
            [
                'driver' => 'Kevin Magnussen',
                'team'   => 'Haas F1 Team',
                'points' => [10, 2, 0, 3, 0, 0, 0, 0, 0, 1, 6, 0, 0, 0, 0, 0, 0, 0, 2, 0, 1, 0],
                'color'  => '#b5b8cb',
            ],
            [
                'driver' => 'Pierre Gasly',
                'team'   => 'AlphaTauri',
                'points' => [0, 4, 2, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, 2, 0, 4, 1, 0, 0, 0, 0, 0],
                'color'  => '#3b53ff',
            ],
            [
                'driver' => 'Lance Stroll',
                'team'   => 'Aston Martin Racing',
                'points' => [0, 0, 0, 1, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 8, 0, 0, 0, 1, 4],
                'color'  => '#208624',
            ],
            [
                'driver' => 'Mich Schumacher',
                'team'   => 'Haas F1 Team',
                'points' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                'color'  => '#b5b8cb',
            ],
            [
                'driver' => 'Yuki Tsunoda',
                'team'   => 'AlphaTauri',
                'points' => [4, 0, 0, 6, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0],
                'color'  => '#3b53ff',
            ],
            [
                'driver' => 'Zhou Guanyu',
                'team'   => 'Alfa Romeo',
                'points' => [1, 0, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0],
                'color'  => '#7a1423',
            ],
            [
                'driver' => 'Alex Albon',
                'team'   => 'Williams',
                'points' => [0, 0, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0],
                'color'  => '#e2fdff',
            ],
            [
                'driver' => 'Nicholas Latifi',
                'team'   => 'Williams',
                'points' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0],
                'color'  => '#e2fdff',
            ],
            [
                'driver' => 'Nyck de Vries',
                'team'   => 'Williams',
                'points' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0],
                'color'  => '#e2fdff',
            ],
            [
                'driver' => 'Nico Hulkenberg',
                'team'   => 'Ferrari',
                'points' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                'color'  => '#208624',
            ],
        ];
    }
}
