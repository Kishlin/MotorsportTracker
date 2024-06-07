<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph;

final readonly class FastestLapDeltaData
{
    /**
     * @param array<array{car_number: string, short_code: string, color: string, fastest: int}> $data
     */
    private function __construct(
        private array $data,
    ) {}

    /**
     * @return array<array{car_number: string, short_code: string, color: string, fastest: int}>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{car_number: string, short_code: string, color: string, fastest: int}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
