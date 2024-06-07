<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

final class EventSessionsDTO
{
    /**
     * @param array<array{session: string, type: string}> $sessions
     */
    private function __construct(
        private readonly array $sessions
    ) {}

    /**
     * @return array<array{session: string, type: string}>
     */
    public function sessions(): array
    {
        return $this->sessions;
    }

    /**
     * @param array<array{session: string, type: string}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
