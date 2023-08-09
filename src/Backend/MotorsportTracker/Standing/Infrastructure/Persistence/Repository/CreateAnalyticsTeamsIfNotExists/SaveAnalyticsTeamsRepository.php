<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsTeamsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists\SaveAnalyticsTeamsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsTeams;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveAnalyticsTeamsRepository extends CoreRepository implements SaveAnalyticsTeamsGateway
{
    public function save(AnalyticsTeams $analytics): void
    {
        $this->persist($analytics);
    }
}
