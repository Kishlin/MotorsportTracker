<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;

interface SaveEventGateway
{
    public function save(Event $event): void;
}
