<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Domain;

use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Series extends Entity
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $shortName,
        private readonly StringValueObject $shortCode,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        StringValueObject $shortCode,
        NullableUuidValueObject $ref,
    ): self {
        return new self(
            $id,
            $name,
            $shortName,
            $shortCode,
            $ref,
        );
    }

    public function mappedData(): array
    {
        return [
            'id'         => $this->id->value(),
            'name'       => $this->name->value(),
            'short_name' => $this->shortName->value(),
            'short_code' => $this->shortCode->value(),
            'ref'        => $this->ref->value(),
        ];
    }

    public function mappedUniqueness(): array
    {
        return [
            'name' => $this->name->value(),
            'ref'  => $this->ref->value(),
        ];
    }
}
