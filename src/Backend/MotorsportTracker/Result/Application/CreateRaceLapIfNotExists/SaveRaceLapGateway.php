<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;

interface SaveRaceLapGateway
{
    public function save(RaceLap $raceLap): void;
}
