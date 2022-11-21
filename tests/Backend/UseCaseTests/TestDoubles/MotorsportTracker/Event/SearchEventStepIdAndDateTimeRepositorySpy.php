<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeViewer;
use Kishlin\Backend\MotorsportTracker\Event\Domain\View\EventStepIdAndDateTimePOPO;

final class SearchEventStepIdAndDateTimeRepositorySpy implements SearchEventStepIdAndDateTimeViewer
{
    public function __construct(
        private EventStepRepositorySpy $eventStepRepositorySpy,
        private StepTypeRepositorySpy $stepTypeRepositorySpy,
        private EventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function search(string $seasonId, string $keyword, string $eventType): ?EventStepIdAndDateTimePOPO
    {
        foreach ($this->eventStepRepositorySpy->all() as $eventStep) {
            $eventTypeObject = $this->stepTypeRepositorySpy->safeGet($eventStep->typeId());

            if (false === str_contains(strtolower($eventTypeObject->label()->value()), strtolower($eventType))) {
                continue;
            }

            $event = $this->eventRepositorySpy->safeGet($eventStep->eventId());

            if (false === str_contains(strtolower($event->label()->value()), strtolower($keyword))) {
                continue;
            }

            return EventStepIdAndDateTimePOPO::fromEntity($eventStep);
        }

        return null;
    }
}
