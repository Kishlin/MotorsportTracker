<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Domain;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class Series extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $shortName,
        private readonly StringValueObject $shortCode,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        StringValueObject $name,
        NullableStringValueObject $shortName,
        StringValueObject $shortCode,
        NullableUuidValueObject $ref,
    ): self {
        return new self(
            $name,
            $shortName,
            $shortCode,
            $ref,
        );
    }

    public function mappedData(): array
    {
        return [
            'name'       => $this->name->value(),
            'short_name' => $this->shortName->value(),
            'short_code' => $this->shortCode->value(),
            'ref'        => $this->ref->value(),
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['name'],
            ['ref'],
        ];
    }
}
