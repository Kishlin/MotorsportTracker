<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;

interface EventGraphGateway
{
    public function save(EventGraph $eventGraph): void;
}
