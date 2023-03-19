<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists\SaveAnalyticsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Analytics;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveAnalyticsRepository extends CoreRepository implements SaveAnalyticsGateway
{
    public function save(Analytics $analytics): void
    {
        $this->persist($analytics);
    }
}
