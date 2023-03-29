<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;

interface EventResultsByRaceGateway
{
    public function save(EventResultsByRace $eventResultsByRace): void;
}
