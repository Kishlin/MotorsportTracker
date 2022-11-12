<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime;

use Kishlin\Backend\MotorsportTracker\Event\Domain\View\EventStepIdAndDateTimePOPO;

interface SearchEventStepIdAndDateTimeViewer
{
    public function search(string $seasonId, string $keyword, string $eventType): ?EventStepIdAndDateTimePOPO;
}
