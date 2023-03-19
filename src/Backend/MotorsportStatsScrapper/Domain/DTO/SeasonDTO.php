<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO;

final class SeasonDTO
{
    private function __construct(
        private readonly string $id,
        private readonly string $ref,
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

    /**
     * @param array{id: string, ref: string} $data
     */
    public static function fromData(array $data): self
    {
        return new self($data['id'], $data['ref']);
    }
}
