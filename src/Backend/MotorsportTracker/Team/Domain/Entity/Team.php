<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Team extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(UuidValueObject $id, NullableUuidValueObject $ref): self
    {
        $team = new self($id, $ref);

        $team->record(new TeamCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, NullableUuidValueObject $ref): self
    {
        return new self($id, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }

    public function mappedData(): array
    {
        return [
            'id'  => $this->id->value(),
            'ref' => $this->ref->value(),
        ];
    }
}
