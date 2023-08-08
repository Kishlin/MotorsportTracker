<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO;

final readonly class SessionDTO
{
    private function __construct(
        private string $id,
        private string $ref,
        private string $event,
        private string $season,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function ref(): string
    {
        return $this->ref;
    }

    public function event(): string
    {
        return $this->event;
    }

    public function season(): string
    {
        return $this->season;
    }

    /**
     * @param array{id: string, ref: string, event: string, season: string} $data
     */
    public static function fromData(array $data): self
    {
        return new self($data['id'], $data['ref'], $data['event'], $data['season']);
    }
}
