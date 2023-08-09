<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO;

final readonly class StandingsDriverDTO
{
    /**
     * @param array{
     *     standings: array<int, array{
     *         driver: array{name: string, shortCode: string, uuid: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             avgFinishPosition: float,
     *             classWins: int,
     *             fastestLaps: int,
     *             finalAppearances: int,
     *             hatTricks: int,
     *             podiums: int,
     *             poles: int,
     *             racesLed: int,
     *             ralliesLed: int,
     *             retirements: int,
     *             semiFinalAppearances: int,
     *             stageWins: int,
     *             starts: int,
     *             top10s: int,
     *             top5s: int,
     *             wins: int,
     *             winsPercentage: float,
     *         },
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
     *         driver: array{name: string, shortCode: string, uuid: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             avgFinishPosition: float,
     *             classWins: int,
     *             fastestLaps: int,
     *             finalAppearances: int,
     *             hatTricks: int,
     *             podiums: int,
     *             poles: int,
     *             racesLed: int,
     *             ralliesLed: int,
     *             retirements: int,
     *             semiFinalAppearances: int,
     *             stageWins: int,
     *             starts: int,
     *             top10s: int,
     *             top5s: int,
     *             wins: int,
     *             winsPercentage: float,
     *         },
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
     *         driver: array{name: string, shortCode: string, uuid: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             avgFinishPosition: float,
     *             classWins: int,
     *             fastestLaps: int,
     *             finalAppearances: int,
     *             hatTricks: int,
     *             podiums: int,
     *             poles: int,
     *             racesLed: int,
     *             ralliesLed: int,
     *             retirements: int,
     *             semiFinalAppearances: int,
     *             stageWins: int,
     *             starts: int,
     *             top10s: int,
     *             top5s: int,
     *             wins: int,
     *             winsPercentage: float,
     *         },
     *     }>
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
