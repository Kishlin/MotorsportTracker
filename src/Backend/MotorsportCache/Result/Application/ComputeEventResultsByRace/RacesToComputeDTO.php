<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

final class RacesToComputeDTO
{
    /**
     * @param array<array{id: string, type: string}> $races
     */
    private function __construct(
        private readonly array $races,
    ) {
    }

    /**
     * @return array<array{id: string, type: string}>
     */
    public function races(): array
    {
        return $this->races;
    }

    /**
     * @param array<array{id: string, type: string}> $races
     */
    public static function fromList(array $races): self
    {
        return new self($races);
    }
}
