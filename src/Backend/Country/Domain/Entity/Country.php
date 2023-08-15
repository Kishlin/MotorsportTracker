<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Country extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly NullableStringValueObject $code,
        private readonly StringValueObject $name,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        NullableStringValueObject $code,
        StringValueObject $name,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $code, $name, $ref);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        NullableStringValueObject $code,
        StringValueObject $name,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $code, $name, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function code(): NullableStringValueObject
    {
        return $this->code;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }

    public function mappedData(): array
    {
        return [
            'id'   => $this->id()->value(),
            'code' => $this->code()->value(),
            'name' => $this->name()->value(),
            'ref'  => $this->ref()->value(),
        ];
    }
}
