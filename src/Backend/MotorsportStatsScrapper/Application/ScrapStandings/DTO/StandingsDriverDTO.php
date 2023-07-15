<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO;

final readonly class StandingsDriverDTO
{
    /**
     * @param array{
     *     standings: array<int, array{
     *         driver: array{name: string, firstName: string, lastName: string, shortCode: string, uuid: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string},
     *         position: int,
     *         totalPoints: float,
     *         raceResults: array<int, array{points: float, finishPosition: int, seasonPoints: float, seasonPosition: int}>
     *     }>
     * } $data
     */
    private function __construct(
        private array $data,
    ) {
    }

    /**
     * @return array{
     *     standings: array<int, array{
     *         driver: array{name: string, firstName: string, lastName: string, shortCode: string, uuid: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string},
     *         position: int,
     *         totalPoints: float,
     *         raceResults: array<int, array{points: float, finishPosition: int, seasonPoints: float, seasonPosition: int}>
     *     }>
     * }
     */
    public function standings(): array
    {
        return $this->data;
    }

    /**
     * @param array{
     *     standings: array<int, array{
     *         driver: array{name: string, firstName: string, lastName: string, shortCode: string, uuid: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string},
     *         position: int,
     *         totalPoints: float,
     *         raceResults: array<int, array{points: float, finishPosition: int, seasonPoints: float, seasonPosition: int}>
     *     }>
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
