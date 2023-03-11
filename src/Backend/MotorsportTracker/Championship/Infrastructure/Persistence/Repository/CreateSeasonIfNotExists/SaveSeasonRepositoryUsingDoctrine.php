<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\SaveSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveSeasonRepositoryUsingDoctrine extends CoreRepository implements SaveSeasonGateway
{
    public function save(Season $season): void
    {
        parent::persist($season);
    }
}
