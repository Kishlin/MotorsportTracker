<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;

interface SaveVenueGateway
{
    public function save(Venue $venue): void;
}
