<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\Gateway\RacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class RacerGatewayUsingDoctrine extends DoctrineRepository implements RacerGateway
{
    public function save(Racer $racer): void
    {
        parent::persist($racer);
    }
}
