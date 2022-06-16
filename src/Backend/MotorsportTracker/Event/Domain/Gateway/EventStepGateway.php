<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;

interface EventStepGateway
{
    public function save(EventStep $eventStep): void;
}
