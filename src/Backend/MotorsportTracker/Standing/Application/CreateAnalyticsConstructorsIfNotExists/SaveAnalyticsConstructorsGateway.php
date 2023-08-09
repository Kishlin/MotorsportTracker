<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsConstructorsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsConstructors;

interface SaveAnalyticsConstructorsGateway
{
    public function save(AnalyticsConstructors $analytics): void;
}
