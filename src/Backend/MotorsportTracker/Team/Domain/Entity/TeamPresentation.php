<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamPresentationCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class TeamPresentation extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $team,
        private readonly UuidValueObject $season,
        private readonly UuidValueObject $country,
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $color,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $team,
        UuidValueObject $season,
        UuidValueObject $country,
        StringValueObject $name,
        NullableStringValueObject $color,
    ): self {
        $team = new self($id, $team, $season, $country, $name, $color);

        $team->record(new TeamPresentationCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $team,
        UuidValueObject $season,
        UuidValueObject $country,
        StringValueObject $name,
        NullableStringValueObject $color,
    ): self {
        return new self($id, $team, $season, $country, $name, $color);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function team(): UuidValueObject
    {
        return $this->team;
    }

    public function season(): UuidValueObject
    {
        return $this->season;
    }

    public function country(): UuidValueObject
    {
        return $this->country;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function color(): NullableStringValueObject
    {
        return $this->color;
    }

    public function mappedData(): array
    {
        return [
            'id'      => $this->id->value(),
            'team'    => $this->team->value(),
            'season'  => $this->season->value(),
            'country' => $this->country->value(),
            'name'    => $this->name->value(),
            'color'   => $this->color->value(),
        ];
    }
}
