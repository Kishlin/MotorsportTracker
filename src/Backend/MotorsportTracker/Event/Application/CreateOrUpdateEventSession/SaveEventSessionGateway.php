<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;

interface SaveEventSessionGateway
{
    public function save(EventSession $eventSession): void;
}
