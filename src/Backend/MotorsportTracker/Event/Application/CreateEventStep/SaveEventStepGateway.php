<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;

interface SaveEventStepGateway
{
    public function save(EventStep $eventStep): void;
}
