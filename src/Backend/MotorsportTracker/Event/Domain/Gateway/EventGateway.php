<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;

interface EventGateway
{
    public function save(Event $event): void;
}
