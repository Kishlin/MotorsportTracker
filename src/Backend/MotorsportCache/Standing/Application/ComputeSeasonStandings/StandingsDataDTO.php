<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings;

final readonly class StandingsDataDTO
{
    /**
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }> $constructorStandings
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: string,
     *     country: null|string,
     * }> $teamStandings
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     short_code: null|string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }> $driverStandings
     */
    private function __construct(
        private array $constructorStandings,
        private array $teamStandings,
        private array $driverStandings,
    ) {
    }

    /**
     * @return array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }>
     */
    public function constructorStandings(): array
    {
        return $this->constructorStandings;
    }

    /**
     * @return array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: string,
     *     country: null|string,
     * }>
     */
    public function teamStandings(): array
    {
        return $this->teamStandings;
    }

    /**
     * @return array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     short_code: null|string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }>
     */
    public function driverStandings(): array
    {
        return $this->driverStandings;
    }

    /**
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }> $constructorStandings
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: string,
     *     country: null|string,
     * }> $teamStandings
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     short_code: null|string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }> $driverStandings
     */
    public static function fromStandings(array $constructorStandings, array $teamStandings, array $driverStandings): self
    {
        return new self($constructorStandings, $teamStandings, $driverStandings);
    }
}
