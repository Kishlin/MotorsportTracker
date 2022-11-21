<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;

interface FindRacerGateway
{
    public function find(string $driverId, string $championship, string $dateInTimespan): ?Racer;
}
