<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

final class LapByLapData
{
    /**
     * @param array<array{label: string, color: string, laps: int, laptimes: string}> $data
     */
    private function __construct(
        private readonly array $data,
    ) {
    }

    /**
     * @return array<array{label: string, color: string, laps: int, laptimes: string}>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{label: string, color: string, laps: int, laptimes: string}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
