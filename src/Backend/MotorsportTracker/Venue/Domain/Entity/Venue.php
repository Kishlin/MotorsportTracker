<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Venue extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly StringValueObject $slug,
        private readonly UuidValueObject $countryId,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $name,
        StringValueObject $slug,
        UuidValueObject $countryId,
    ): self {
        return new self($id, $name, $slug, $countryId);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $name,
        StringValueObject $slug,
        UuidValueObject $countryId,
    ): self {
        return new self($id, $name, $slug, $countryId);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function slug(): StringValueObject
    {
        return $this->slug;
    }

    public function countryId(): UuidValueObject
    {
        return $this->countryId;
    }
}
