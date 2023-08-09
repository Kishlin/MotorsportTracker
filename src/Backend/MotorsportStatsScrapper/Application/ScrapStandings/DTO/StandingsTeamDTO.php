<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO;

final readonly class StandingsTeamDTO
{
    /**
     * @param array{
     *     standings: array<int, array{
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         icon: string,
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             classWins: int,
     *             fastestLaps: int,
     *             finalAppearances: int,
     *             finishes1And2: int,
     *             podiums: int,
     *             poles: int,
     *             qualifies1And2: int,
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
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         icon: string,
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             classWins: int,
     *             fastestLaps: int,
     *             finalAppearances: int,
     *             finishes1And2: int,
     *             podiums: int,
     *             poles: int,
     *             qualifies1And2: int,
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
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         icon: string,
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             classWins: int,
     *             fastestLaps: int,
     *             finalAppearances: int,
     *             finishes1And2: int,
     *             podiums: int,
     *             poles: int,
     *             qualifies1And2: int,
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
