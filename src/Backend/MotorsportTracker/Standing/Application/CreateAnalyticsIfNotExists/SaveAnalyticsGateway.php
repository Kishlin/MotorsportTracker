<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Analytics;

interface SaveAnalyticsGateway
{
    public function save(Analytics $analytics): void;
}
