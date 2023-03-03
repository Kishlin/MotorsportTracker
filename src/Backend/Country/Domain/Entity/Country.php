<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Country extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $code,
        private readonly StringValueObject $name,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $code,
        StringValueObject $name,
    ): self {
        return new self($id, $code, $name);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $code,
        StringValueObject $name,
    ): self {
        return new self($id, $code, $name);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function code(): StringValueObject
    {
        return $this->code;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }
}
