<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\SaveVenueGateway;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveVenueGatewayUsingDoctrine extends CoreRepository implements SaveVenueGateway
{
    public function save(Venue $venue): void
    {
        parent::persist($venue);
    }
}
