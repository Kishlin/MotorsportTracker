<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class AnalyticsIdentity implements Mapped
{
    private function __construct(
        private string $season,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'season' => $this->season,
        ];
    }

    public static function fromData(string $season): self
    {
        return new self($season);
    }
}
