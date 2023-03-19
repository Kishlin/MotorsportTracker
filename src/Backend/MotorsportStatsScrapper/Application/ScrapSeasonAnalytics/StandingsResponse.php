<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics;

final class StandingsResponse
{
    /**
     * @param array{
     *     standings: array<array{
     *         driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         nationality: array{name: string, uuid: string, picture: string},
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
     *     }>,
     * } $data
     */
    private function __construct(
        private readonly array $data,
    ) {
    }

    /**
     * @return array{
     *     standings: array<array{
     *         driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         nationality: array{name: string, uuid: string, picture: string},
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
     *     }>,
     * }
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array{
     *     standings: array<array{
     *         driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         nationality: array{name: string, uuid: string, picture: string},
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
     *     }>,
     * } $data
     */
    public static function withStandings(array $data): self
    {
        return new self($data);
    }
}
