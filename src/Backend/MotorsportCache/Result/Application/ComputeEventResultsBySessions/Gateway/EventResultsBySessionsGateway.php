<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;

interface EventResultsBySessionsGateway
{
    public function save(EventResultsByRace $eventResultsByRace): void;
}
