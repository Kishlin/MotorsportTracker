<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\DomainEvent\VenueCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Venue extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly UuidValueObject $countryId,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $name,
        UuidValueObject $countryId,
        NullableUuidValueObject $ref,
    ): self {
        $venue = new self($id, $name, $countryId, $ref);

        $venue->record(new VenueCreatedDomainEvent($id));

        return $venue;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $name,
        UuidValueObject $countryId,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $name, $countryId, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
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
            'id'      => $this->id->value(),
            'name'    => $this->name->value(),
            'country' => $this->countryId->value(),
            'ref'     => $this->ref->value(),
        ];
    }
}
