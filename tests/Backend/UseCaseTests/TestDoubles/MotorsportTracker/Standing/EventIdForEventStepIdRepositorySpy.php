<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded\EventIdOfEventStepIdReader;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;

final class EventIdForEventStepIdRepositorySpy implements EventIdOfEventStepIdReader
{
    public function __construct(
        private EventStepRepositorySpy $eventStepRepositorySpy,
    ) {
    }

    public function eventIdForEventStepId(UuidValueObject $eventStepId): string
    {
        $eventStep = $this->eventStepRepositorySpy->get(EventStepId::fromOther($eventStepId));
        assert(null !== $eventStep);

        return $eventStep->eventId()->value();
    }
}