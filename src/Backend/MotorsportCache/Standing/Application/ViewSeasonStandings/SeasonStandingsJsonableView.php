<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings;

use JsonException;
use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class SeasonStandingsJsonableView extends JsonableView
{
    /**
     * @var array{
     *     constructor: array<string, array<array{
     *         id: string,
     *         name: string,
     *         position: int,
     *         points: float,
     *         color: null|string,
     *         country: null|array{id: string, name: string, code: string},
     *     }>>,
     *     team: array<string, array<array{
     *         id: string,
     *         name: string,
     *         position: int,
     *         points: float,
     *         color: string,
     *         country: null|array{id: string, name: string, code: string},
     *     }>>,
     *     driver: array<string, array<array{
     *         id: string,
     *         name: string,
     *         short_code: null|string,
     *         position: int,
     *         points: float,
     *         color: null|string,
     *         country: null|array{id: string, name: string, code: string},
     *     }>>,
     * }
     */
    private array $standings;

    /**
     * @return array{
     *     constructor: array<string, array<array{
     *         id: string,
     *         name: string,
     *         position: int,
     *         points: float,
     *         color: null|string,
     *         country: null|array{id: string, name: string, code: string},
     *     }>>,
     *     team: array<string, array<array{
     *         id: string,
     *         name: string,
     *         position: int,
     *         points: float,
     *         color: string,
     *         country: null|array{id: string, name: string, code: string},
     *     }>>,
     *     driver: array<string, array<array{
     *         id: string,
     *         name: string,
     *         short_code: null|string,
     *         position: int,
     *         points: float,
     *         color: null|string,
     *         country: null|array{id: string, name: string, code: string},
     *     }>>,
     * }
     */
    public function toArray(): array
    {
        return $this->standings;
    }

    /**
     * @param array{
     *     constructor: string,
     *     team: string,
     *     driver: string,
     * } $source
     *
     * @throws JsonException
     */
    public static function fromSource(array $source): self
    {
        /**
         * @var array<string, array<array{
         *     id: string,
         *     name: string,
         *     position: int,
         *     points: float,
         *     color: null|string,
         *     country: null|array{id: string, name: string, code: string},
         * }>> $constructor
         */
        $constructor = json_decode($source['constructor'], true, 512, JSON_THROW_ON_ERROR);

        /**
         * @var array<string, array<array{
         *     id: string,
         *     name: string,
         *     position: int,
         *     points: float,
         *     color: string,
         *     country: null|array{id: string, name: string, code: string},
         * }>> $team
         */
        $team = json_decode($source['team'], true, 512, JSON_THROW_ON_ERROR);

        /**
         * @var array<string, array<array{
         *     id: string,
         *     name: string,
         *     short_code: null|string,
         *     position: int,
         *     points: float,
         *     color: null|string,
         *     country: null|array{id: string, name: string, code: string},
         * }>> $driver
         */
        $driver = json_decode($source['driver'], true, 512, JSON_THROW_ON_ERROR);

        $view = new self();

        $view->standings = [
            'constructor' => $constructor,
            'team'        => $team,
            'driver'      => $driver,
        ];

        return $view;
    }
}
