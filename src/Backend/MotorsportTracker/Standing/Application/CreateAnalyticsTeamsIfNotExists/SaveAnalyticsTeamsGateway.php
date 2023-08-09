<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsTeams;

interface SaveAnalyticsTeamsGateway
{
    public function save(AnalyticsTeams $analytics): void;
}
