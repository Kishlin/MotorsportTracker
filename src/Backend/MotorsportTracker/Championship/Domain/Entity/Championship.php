<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Championship extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $name,
        private readonly StringValueObject $slug,
    ) {
    }

    public static function create(UuidValueObject $id, StringValueObject $name, StringValueObject $slug): self
    {
        return new self($id, $name, $slug);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, StringValueObject $name, StringValueObject $slug): self
    {
        return new self($id, $name, $slug);
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
}
