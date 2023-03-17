<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons;

final class SeasonsResponse
{
    /**
     * @param array<array{name: string, uuid: string, year: int, endYear: ?int, status: string}> $seasons
     */
    private function __construct(
        private readonly array $seasons,
    ) {
    }

    /**
     * @return array<array{name: string, uuid: string, year: int, endYear: ?int, status: string}>
     */
    public function seasons(): array
    {
        return $this->seasons;
    }

    /**
     * @param array<array{name: string, uuid: string, year: int, endYear: ?int, status: string}> $seasons
     */
    public static function withSeasons(array $seasons): self
    {
        return new self($seasons);
    }
}
