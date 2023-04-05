<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\EventDataDTO;

interface EventDataGateway
{
    public function findAll(): EventDataDTO;
}
