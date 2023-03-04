<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueGateway;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class VenueGatewayUsingDoctrine extends CoreRepository implements VenueGateway
{
    public function save(Venue $venue): void
    {
        parent::persist($venue);
    }
}
