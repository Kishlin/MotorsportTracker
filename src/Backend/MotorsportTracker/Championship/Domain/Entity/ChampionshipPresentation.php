<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipPresentationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationColor;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationIcon;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class ChampionshipPresentation extends AggregateRoot
{
    private function __construct(
        private readonly ChampionshipPresentationId $id,
        private readonly ChampionshipPresentationChampionshipId $championshipId,
        private readonly ChampionshipPresentationIcon $icon,
        private readonly ChampionshipPresentationColor $color,
        private readonly ChampionshipPresentationCreatedOn $createdOn,
    ) {
    }

    public static function create(
        ChampionshipPresentationId $id,
        ChampionshipPresentationChampionshipId $championshipId,
        ChampionshipPresentationIcon $icon,
        ChampionshipPresentationColor $color,
        ChampionshipPresentationCreatedOn $createdOn,
    ): self {
        $championshipIcon = new self($id, $championshipId, $icon, $color, $createdOn);

        $championshipIcon->record(new ChampionshipPresentationCreatedDomainEvent($id));

        return $championshipIcon;
    }

    public static function instance(
        ChampionshipPresentationId $id,
        ChampionshipPresentationChampionshipId $championshipId,
        ChampionshipPresentationIcon $icon,
        ChampionshipPresentationColor $color,
        ChampionshipPresentationCreatedOn $createdOn,
    ): self {
        return new self($id, $championshipId, $icon, $color, $createdOn);
    }

    public function id(): ChampionshipPresentationId
    {
        return $this->id;
    }

    public function championshipId(): ChampionshipPresentationChampionshipId
    {
        return $this->championshipId;
    }

    public function icon(): ChampionshipPresentationIcon
    {
        return $this->icon;
    }

    public function color(): ChampionshipPresentationColor
    {
        return $this->color;
    }

    public function createdOn(): ChampionshipPresentationCreatedOn
    {
        return $this->createdOn;
    }
}
