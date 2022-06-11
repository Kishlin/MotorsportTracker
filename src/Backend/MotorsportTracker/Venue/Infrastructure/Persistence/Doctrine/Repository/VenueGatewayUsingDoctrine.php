<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Gateway\VenueGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class VenueGatewayUsingDoctrine extends DoctrineRepository implements VenueGateway
{
    public function save(Venue $venue): void
    {
        parent::persist($venue);
    }
}
