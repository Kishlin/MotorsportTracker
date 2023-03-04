<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\SaveSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveSeasonGatewayUsingDoctrine extends CoreRepository implements SaveSeasonGateway
{
    public function save(Season $season): void
    {
        parent::persist($season);
    }
}
