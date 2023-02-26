<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStandingsView;

interface TeamStandingsViewsGateway
{
    public function save(TeamStandingsView $view): void;
}
