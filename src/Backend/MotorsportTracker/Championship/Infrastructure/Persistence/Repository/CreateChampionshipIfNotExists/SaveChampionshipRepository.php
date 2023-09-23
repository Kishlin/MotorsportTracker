<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\SaveChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveChampionshipRepository extends CoreRepository implements SaveChampionshipGateway
{
    public function save(Series $championship): void
    {
        parent::persist($championship);
    }
}
