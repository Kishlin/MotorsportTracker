<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\SaveChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveChampionshipRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveChampionshipGateway
{
    public function save(Championship $championship): void
    {
        parent::persist($championship);
    }
}
