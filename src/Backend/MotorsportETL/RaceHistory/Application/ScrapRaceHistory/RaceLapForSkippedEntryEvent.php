<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class RaceLapForSkippedEntryEvent implements ApplicationEvent
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
        private string $session,
        private string $carNumber,
        private array $carPosition,
    ) {
    }

    public function session(): string
    {
        return $this->session;
    }

    public function carNumber(): string
    {
        return $this->carNumber;
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
     * }
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
    public static function fromScalars(string $session, string $carNumber, array $carPosition): self
    {
        return new self($session, $carNumber, $carPosition);
    }
}
