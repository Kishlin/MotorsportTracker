<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph;

interface PositionChangeDataGateway
{
    public function findForSession(string $session): PositionChangeData;
}
