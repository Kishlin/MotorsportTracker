<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Domain;

use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Season extends Entity
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StrictlyPositiveIntValueObject $year,
        private readonly UuidValueObject $series,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StrictlyPositiveIntValueObject $year,
        UuidValueObject $series,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $year, $series, $ref);
    }

    public function mappedData(): array
    {
        return [
            'id'     => $this->id->value(),
            'year'   => $this->year->value(),
            'series' => $this->series->value(),
            'ref'    => $this->ref->value(),
        ];
    }

    public function mappedUniqueness(): array
    {
        return [
            'year'   => $this->year->value(),
            'series' => $this->series->value(),
        ];
    }
}
