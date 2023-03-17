<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons;

final class SeriesDTO
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
    public static function fromScalars(array $data): self
    {
        return new self($data['id'], $data['ref']);
    }
}
