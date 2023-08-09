<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsConstructorsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsConstructorsIfNotExists\SaveAnalyticsConstructorsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsConstructors;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveAnalyticsConstructorsRepository extends CoreRepository implements SaveAnalyticsConstructorsGateway
{
    public function save(AnalyticsConstructors $analytics): void
    {
        $this->persist($analytics);
    }
}
