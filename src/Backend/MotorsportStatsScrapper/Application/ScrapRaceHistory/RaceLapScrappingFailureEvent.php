<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class RaceLapScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     entryUuid: string,
     *     position: int,
     *     pit: bool,
     *     time: int,
     *     gap: array{
     *         timeToLead: ?int,
     *         lapsToLead: ?int,
     *         timeToNext: ?int,
     *         lapsToNext: ?int,
     *     },
     *     tyreDetail: array{
     *         type: string,
     *         wear: string,
     *         laps: int,
     *     }[],
     * } $carPosition
     */
    private function __construct(
        private readonly array $carPosition,
    ) {
    }

    /**
     * @return array{
     *     entryUuid: string,
     *     position: int,
     *     pit: bool,
     *     time: int,
     *     gap: array{
     *         timeToLead: ?int,
     *         lapsToLead: ?int,
     *         timeToNext: ?int,
     *         lapsToNext: ?int,
     *     },
     *     tyreDetail: array{
     *         type: string,
     *         wear: string,
     *         laps: int,
     *     }[],
     * } $event
     */
    public function carPosition(): array
    {
        return $this->carPosition;
    }

    /**
     * @param array{
     *     entryUuid: string,
     *     position: int,
     *     pit: bool,
     *     time: int,
     *     gap: array{
     *         timeToLead: ?int,
     *         lapsToLead: ?int,
     *         timeToNext: ?int,
     *         lapsToNext: ?int,
     *     },
     *     tyreDetail: array{
     *         type: string,
     *         wear: string,
     *         laps: int,
     *     }[],
     * } $carPosition
     */
    public static function forCarPosition(array $carPosition): self
    {
        return new self($carPosition);
    }
}
