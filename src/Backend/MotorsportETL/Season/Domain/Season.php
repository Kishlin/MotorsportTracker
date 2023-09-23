<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Domain;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Season extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly StrictlyPositiveIntValueObject $year,
        private readonly UuidValueObject $series,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        StrictlyPositiveIntValueObject $year,
        UuidValueObject $series,
        NullableUuidValueObject $ref,
    ): self {
        return new self($year, $series, $ref);
    }

    public function mappedData(): array
    {
        return [
            'year'   => $this->year->value(),
            'series' => $this->series->value(),
            'ref'    => $this->ref->value(),
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['year', 'series'],
        ];
    }
}
