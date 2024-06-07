<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final readonly class StandingData implements Mapped
{
    private function __construct(
        private int $position,
        private float $points,
    ) {}

    public function position(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->position);
    }

    public function points(): FloatValueObject
    {
        return new FloatValueObject($this->points);
    }

    public function mappedData(): array
    {
        return [
            'position' => $this->position,
            'points'   => $this->points,
        ];
    }

    public static function fromData(int $position, float $points): self
    {
        return new self($position, $points);
    }
}
