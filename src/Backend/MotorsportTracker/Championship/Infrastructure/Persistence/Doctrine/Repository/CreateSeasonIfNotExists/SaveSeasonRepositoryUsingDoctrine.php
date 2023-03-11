<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\SaveSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveSeasonRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveSeasonGateway
{
    public function save(Season $season): void
    {
        parent::persist($season);
    }
}
