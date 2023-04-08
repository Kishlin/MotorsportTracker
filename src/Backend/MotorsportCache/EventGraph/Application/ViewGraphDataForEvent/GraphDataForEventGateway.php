<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent;

interface GraphDataForEventGateway
{
    public function viewForEvent(string $event): GraphDataForEventJsonableView;
}
