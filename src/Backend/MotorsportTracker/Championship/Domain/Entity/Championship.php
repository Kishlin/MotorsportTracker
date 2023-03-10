<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Championship extends AggregateRoot
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
        $championship = new self($id, $name, $shortName, $shortCode, $ref);

        $championship->record(new ChampionshipCreatedDomainEvent($id));

        return $championship;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        StringValueObject $shortCode,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $name, $shortName, $shortCode, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function shortName(): NullableStringValueObject
    {
        return $this->shortName;
    }

    public function shortCode(): StringValueObject
    {
        return $this->shortCode;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }
}
