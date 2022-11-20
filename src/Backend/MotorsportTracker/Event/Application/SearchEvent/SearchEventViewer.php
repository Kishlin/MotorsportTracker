<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;

interface SearchEventViewer
{
    public function search(string $seasonId, string $keyword): ?EventId;
}
