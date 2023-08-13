<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph;

interface TyreHistoryDataGateway
{
    public function findForSession(string $session): TyreHistoryData;
}
