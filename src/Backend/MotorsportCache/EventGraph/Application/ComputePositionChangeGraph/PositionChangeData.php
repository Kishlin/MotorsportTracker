<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph;

final readonly class PositionChangeData
{
    /**
     * @param array<array{car_number: string, short_code: string, color: string, grid: int, finish: int, changes: int}> $data
     */
    private function __construct(
        private array $data,
    ) {}

    /**
     * @return array<array{car_number: string, short_code: string, color: string, grid: int, finish: int, changes: int}>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{car_number: string, short_code: string, color: string, grid: int, finish: int, changes: int}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
