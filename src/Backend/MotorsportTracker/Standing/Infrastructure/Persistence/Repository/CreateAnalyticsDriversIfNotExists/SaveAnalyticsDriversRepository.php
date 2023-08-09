<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsDriversIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists\SaveAnalyticsDriversGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveAnalyticsDriversRepository extends CoreRepository implements SaveAnalyticsDriversGateway
{
    public function save(AnalyticsDrivers $analytics): void
    {
        $this->persist($analytics);
    }
}
