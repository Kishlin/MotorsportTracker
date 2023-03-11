<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipPresentation;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\SaveChampionshipPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveChampionshipPresentationRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveChampionshipPresentationGateway
{
    public function save(ChampionshipPresentation $championshipPresentation): void
    {
        $this->persist($championshipPresentation);
    }
}
