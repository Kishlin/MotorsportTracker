<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class TyreHistoryWithNullValuesEvent implements ApplicationEvent
{
    /**
     * @param array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int} $series
     */
    private function __construct(
        private string $session,
        private array $series,
    ) {}

    public function session(): string
    {
        return $this->session;
    }

    /**
     * @return array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int}
     */
    public function series(): array
    {
        return $this->series;
    }

    /**
     * @param array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int} $series
     */
    public static function forSeries(string $session, array $series): self
    {
        return new self($session, $series);
    }
}
