<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;

interface SaveChampionshipPresentationGateway
{
    public function save(ChampionshipPresentation $championshipPresentation): void;
}
