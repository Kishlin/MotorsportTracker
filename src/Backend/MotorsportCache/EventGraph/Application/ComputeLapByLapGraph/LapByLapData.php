<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

final readonly class LapByLapData
{
    /**
     * @param array<array{car_number: string, short_code: string, color: string, laps: string, max: float, classified_status: string}> $data
     */
    private function __construct(
        private array $data,
    ) {}

    /**
     * @return array<array{car_number: string, short_code: string, color: string, laps: string, max: float, classified_status: string}>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{car_number: string, short_code: string, color: string, laps: string, max: float, classified_status: string}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
