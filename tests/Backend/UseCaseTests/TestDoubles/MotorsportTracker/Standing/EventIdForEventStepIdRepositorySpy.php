<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\EventIdOfEventStepIdReader;
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
        return $this->eventStepRepositorySpy->safeGet(EventStepId::fromOther($eventStepId))->eventId()->value();
    }
}
