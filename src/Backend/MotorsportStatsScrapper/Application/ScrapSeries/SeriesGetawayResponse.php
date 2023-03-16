<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeries;

final class SeriesGetawayResponse
{
    /**
     * @param array<array{name: string, uuid: string, shortName: ?string, shortCode: string, category: ?string}> $series
     */
    private function __construct(
        private readonly array $series,
    ) {
    }

    /**
     * @return array<array{name: string, uuid: string, shortName: ?string, shortCode: string, category: ?string}>
     */
    public function series(): array
    {
        return $this->series;
    }

    /**
     * @param array<array{name: string, uuid: string, shortName: ?string, shortCode: string, category: ?string}> $data
     */
    public static function withData(array $data): self
    {
        return new self($data);
    }
}
