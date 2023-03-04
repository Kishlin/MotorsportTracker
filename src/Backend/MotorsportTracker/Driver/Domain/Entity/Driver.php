<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Driver extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly UuidValueObject $countryId,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $name,
        UuidValueObject $countryId,
    ): self {
        return new self($id, $name, $countryId);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $name,
        UuidValueObject $countryId,
    ): self {
        return new self($id, $name, $countryId);
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
}
