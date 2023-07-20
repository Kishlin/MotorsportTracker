<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Constructor extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(UuidValueObject $id, StringValueObject $name, NullableUuidValueObject $ref): self
    {
        $team = new self($id, $name, $ref);

        $team->record(new TeamCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, StringValueObject $name, NullableUuidValueObject $ref): self
    {
        return new self($id, $name, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
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
            'id'   => $this->id->value(),
            'name' => $this->name->value(),
            'ref'  => $this->ref->value(),
        ];
    }
}
