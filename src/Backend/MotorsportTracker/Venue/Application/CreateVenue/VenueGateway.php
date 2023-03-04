<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;

interface VenueGateway
{
    public function save(Venue $venue): void;
}
