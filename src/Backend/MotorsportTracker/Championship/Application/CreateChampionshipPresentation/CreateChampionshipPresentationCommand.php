<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationColor;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationIcon;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateChampionshipPresentationCommand implements Command
{
    private function __construct(
        private string $championshipId,
        private string $icon,
        private string $color,
    ) {
    }

    public function championshipId(): ChampionshipPresentationChampionshipId
    {
        return new ChampionshipPresentationChampionshipId($this->championshipId);
    }

    public function icon(): ChampionshipPresentationIcon
    {
        return new ChampionshipPresentationIcon($this->icon);
    }

    public function color(): ChampionshipPresentationColor
    {
        return new ChampionshipPresentationColor($this->color);
    }

    public static function fromScalars(string $championshipId, string $icon, string $color): self
    {
        return new self($championshipId, $icon, $color);
    }
}
