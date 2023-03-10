<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Team extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $country,
        private readonly StringValueObject $slug,
        private readonly StringValueObject $name,
        private readonly StringValueObject $code,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $country,
        StringValueObject $slug,
        StringValueObject $name,
        StringValueObject $code,
    ): self {
        $team = new self($id, $country, $slug, $name, $code);

        $team->record(new TeamCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $country,
        StringValueObject $slug,
        StringValueObject $name,
        StringValueObject $code,
    ): self {
        return new self($id, $country, $slug, $name, $code);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function country(): UuidValueObject
    {
        return $this->country;
    }

    public function slug(): StringValueObject
    {
        return $this->slug;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function code(): StringValueObject
    {
        return $this->code;
    }
}
