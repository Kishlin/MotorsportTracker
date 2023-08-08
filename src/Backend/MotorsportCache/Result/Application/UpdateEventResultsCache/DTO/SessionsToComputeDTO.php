<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\DTO;

final readonly class SessionsToComputeDTO
{
    /**
     * @param array<array{id: string, type: string}> $sessions
     */
    private function __construct(
        private array $sessions,
    ) {
    }

    /**
     * @return array<array{id: string, type: string}>
     */
    public function sessions(): array
    {
        return $this->sessions;
    }

    /**
     * @param array<array{id: string, type: string}> $sessions
     */
    public static function fromList(array $sessions): self
    {
        return new self($sessions);
    }
}
