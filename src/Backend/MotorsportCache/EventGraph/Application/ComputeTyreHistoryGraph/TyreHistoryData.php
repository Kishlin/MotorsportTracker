<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph;

final readonly class TyreHistoryData
{
    /**
     * @param array<array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int}> $data
     */
    private function __construct(
        private array $data,
    ) {
    }

    /**
     * @return array<array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int}>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
