<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class StandingsIdentity implements Mapped
{
    private function __construct(
        private string $season,
        private ?string $seriesClass,
    ) {}

    public function mappedData(): array
    {
        return [
            'season'       => $this->season,
            'series_class' => $this->seriesClass,
        ];
    }

    public static function fromData(string $season, ?string $seriesClass): self
    {
        return new self($season, $seriesClass);
    }
}
