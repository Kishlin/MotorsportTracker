<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Championship\Gateway\ChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class ChampionshipGatewayUsingDoctrine extends DoctrineRepository implements ChampionshipGateway
{
    public function save(Championship $championship): void
    {
        parent::persist($championship);
    }
}
