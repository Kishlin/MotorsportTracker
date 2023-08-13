<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent;

interface HistoriesDataGateway
{
    public function findForSession(string $session): HistoriesDataDTO;
}
