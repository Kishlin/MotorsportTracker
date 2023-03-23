<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class ClassificationScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     finishPosition: int,
     *     gridPosition: int,
     *     carNumber: int,
     *     drivers: array<array{
     *         name: string,
     *         firstName: string,
     *         lastName: string,
     *         shortCode: string,
     *         colour: null|string,
     *         uuid: string,
     *         picture: string,
     *     }>,
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     laps: int,
     *     points: float,
     *     time: float,
     *     classifiedStatus: string,
     *     avgLapSpeed: float,
     *     fastestLapTime: float,
     *     gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *     best: array{lap: int, time: float, fastest: bool, speed: null}
     * } $classification
     */
    private function __construct(
        private readonly array $classification,
    ) {
    }

    /**
     * @return array{
     *     finishPosition: int,
     *     gridPosition: int,
     *     carNumber: int,
     *     drivers: array<array{
     *         name: string,
     *         firstName: string,
     *         lastName: string,
     *         shortCode: string,
     *         colour: null|string,
     *         uuid: string,
     *         picture: string,
     *     }>,
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     laps: int,
     *     points: float,
     *     time: float,
     *     classifiedStatus: string,
     *     avgLapSpeed: float,
     *     fastestLapTime: float,
     *     gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *     best: array{lap: int, time: float, fastest: bool, speed: null}
     * } $event
     */
    public function classification(): array
    {
        return $this->classification;
    }

    /**
     * @param array{
     *     finishPosition: int,
     *     gridPosition: int,
     *     carNumber: int,
     *     drivers: array<array{
     *         name: string,
     *         firstName: string,
     *         lastName: string,
     *         shortCode: string,
     *         colour: null|string,
     *         uuid: string,
     *         picture: string,
     *     }>,
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     laps: int,
     *     points: float,
     *     time: float,
     *     classifiedStatus: string,
     *     avgLapSpeed: float,
     *     fastestLapTime: float,
     *     gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *     best: array{lap: int, time: float, fastest: bool, speed: null}
     * } $classification
     */
    public static function forClassification(array $classification): self
    {
        return new self($classification);
    }
}
