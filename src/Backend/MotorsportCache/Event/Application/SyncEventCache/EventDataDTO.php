<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache;

final class EventDataDTO
{
    /**
     * @param array<array{championship: string, year: int, event: string}> $data
     */
    private function __construct(
        private readonly array $data,
    ) {
    }

    /**
     * @return array<array{championship: string, year: int, event: string}>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{championship: string, year: int, event: string}> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
