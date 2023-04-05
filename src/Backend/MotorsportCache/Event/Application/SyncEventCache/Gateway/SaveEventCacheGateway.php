<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway;

use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;

interface SaveEventCacheGateway
{
    public function save(EventCached $eventCached): void;
}
