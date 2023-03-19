<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class SeasonAnalyticsScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         avgFinishPosition: float,
     *         classWins: int,
     *         fastestLaps: int,
     *         finalAppearances: int,
     *         hatTricks: int,
     *         podiums: int,
     *         poles: int,
     *         racesLed: int,
     *         ralliesLed: int,
     *         retirements: int,
     *         semiFinalAppearances: int,
     *         stageWins: int,
     *         starts: int,
     *         top10s: int,
     *         top5s: int,
     *         wins: int,
     *         winsPercentage: float,
     *     },
     * } $standing
     */
    private function __construct(
        private readonly array $standing,
    ) {
    }

    /**
     * @return array{
     *     driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         avgFinishPosition: float,
     *         classWins: int,
     *         fastestLaps: int,
     *         finalAppearances: int,
     *         hatTricks: int,
     *         podiums: int,
     *         poles: int,
     *         racesLed: int,
     *         ralliesLed: int,
     *         retirements: int,
     *         semiFinalAppearances: int,
     *         stageWins: int,
     *         starts: int,
     *         top10s: int,
     *         top5s: int,
     *         wins: int,
     *         winsPercentage: float,
     *     },
     * } $standing
     */
    public function standing(): array
    {
        return $this->standing;
    }

    /**
     * @param array{
     *     driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         avgFinishPosition: float,
     *         classWins: int,
     *         fastestLaps: int,
     *         finalAppearances: int,
     *         hatTricks: int,
     *         podiums: int,
     *         poles: int,
     *         racesLed: int,
     *         ralliesLed: int,
     *         retirements: int,
     *         semiFinalAppearances: int,
     *         stageWins: int,
     *         starts: int,
     *         top10s: int,
     *         top5s: int,
     *         wins: int,
     *         winsPercentage: float,
     *     },
     * } $standing
     */
    public static function forStanding(array $standing): self
    {
        return new self($standing);
    }
}
