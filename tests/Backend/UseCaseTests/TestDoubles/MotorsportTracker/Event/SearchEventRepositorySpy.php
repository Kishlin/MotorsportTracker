<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventViewer;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SearchEventRepositorySpy implements SearchEventViewer
{
    public function __construct(
        private readonly EventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function search(string $seasonId, string $keyword): ?UuidValueObject
    {
        foreach ($this->eventRepositorySpy->all() as $event) {
            if (false === str_contains(strtolower($event->name()->value()), strtolower($keyword))) {
                continue;
            }

            return $event->id();
        }

        return null;
    }
}
