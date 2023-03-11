<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Driver extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly StringValueObject $shortCode,
        private readonly UuidValueObject $countryId,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $name,
        StringValueObject $shortCode,
        UuidValueObject $countryId,
        NullableUuidValueObject $ref,
    ): self {
        $driver = new self($id, $name, $shortCode, $countryId, $ref);

        $driver->record(new DriverCreatedDomainEvent($id));

        return $driver;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $name,
        StringValueObject $shortCode,
        UuidValueObject $countryId,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $name, $shortCode, $countryId, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function shortCode(): StringValueObject
    {
        return $this->shortCode;
    }

    public function countryId(): UuidValueObject
    {
        return $this->countryId;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }

    public function mappedData(): array
    {
        return [
            'id'         => $this->id->value(),
            'name'       => $this->name->value(),
            'short_code' => $this->shortCode->value(),
            'country'    => $this->countryId->value(),
            'ref'        => $this->ref->value(),
        ];
    }
}
