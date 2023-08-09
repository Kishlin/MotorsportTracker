<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final readonly class AnalyticsConstructorsStatsDTO
{
    private function __construct(
        private int $wins,
    ) {
    }

    public function wins(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->wins);
    }

    public static function fromScalars(int $wins): self
    {
        return new self($wins);
    }
}
