<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventViewer;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;

final class SearchEventRepositorySpy implements SearchEventViewer
{
    public function __construct(
        private EventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function search(string $seasonId, string $keyword): ?EventId
    {
        foreach ($this->eventRepositorySpy->all() as $event) {
            if (false === str_contains(strtolower($event->label()->value()), strtolower($keyword))) {
                continue;
            }

            return $event->id();
        }

        return null;
    }
}
