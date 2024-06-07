<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipPresentationCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ChampionshipPresentation extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $championshipId,
        private readonly StringValueObject $icon,
        private readonly StringValueObject $color,
        private readonly DateTimeValueObject $createdOn,
    ) {}

    public static function create(
        UuidValueObject $id,
        UuidValueObject $championshipId,
        StringValueObject $icon,
        StringValueObject $color,
        DateTimeValueObject $createdOn,
    ): self {
        $championshipIcon = new self($id, $championshipId, $icon, $color, $createdOn);

        $championshipIcon->record(new ChampionshipPresentationCreatedDomainEvent($id));

        return $championshipIcon;
    }

    public static function instance(
        UuidValueObject $id,
        UuidValueObject $championshipId,
        StringValueObject $icon,
        StringValueObject $color,
        DateTimeValueObject $createdOn,
    ): self {
        return new self($id, $championshipId, $icon, $color, $createdOn);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function championshipId(): UuidValueObject
    {
        return $this->championshipId;
    }

    public function icon(): StringValueObject
    {
        return $this->icon;
    }

    public function color(): StringValueObject
    {
        return $this->color;
    }

    public function createdOn(): DateTimeValueObject
    {
        return $this->createdOn;
    }

    public function mappedData(): array
    {
        return [
            'id'           => $this->id->value(),
            'championship' => $this->championshipId->value(),
            'icon'         => $this->icon->value(),
            'color'        => $this->color->value(),
            'created_on'   => $this->createdOn->value()->format('Y-m-d H:i:s'),
        ];
    }
}
