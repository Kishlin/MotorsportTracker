<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepAtTheSameTimeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepWithTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\EventStepGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property EventStep[] $objects
 *
 * @method EventStep get(EventStepId $id)
 */
final class EventStepRepositorySpy extends AbstractRepositorySpy implements EventStepGateway, EventHasStepWithTypeGateway, EventHasStepAtTheSameTimeGateway
{
    /**
     * @throws Exception
     */
    public function save(EventStep $eventStep): void
    {
        if (
            $this->eventHasStepAtTheSameTime($eventStep->eventId(), $eventStep->dateTime())
            || $this->eventHasStepWithType($eventStep->eventId(), $eventStep->typeId())
        ) {
            throw new Exception();
        }

        $this->objects[$eventStep->id()->value()] = $eventStep;
    }

    public function eventHasStepWithType(EventStepEventId $eventId, EventStepTypeId $typeId): bool
    {
        foreach ($this->objects as $existingEventStep) {
            if ($existingEventStep->eventId()->equals($eventId) && $existingEventStep->typeId()->equals($typeId)) {
                return true;
            }
        }

        return false;
    }

    public function eventHasStepAtTheSameTime(EventStepEventId $eventId, EventStepDateTime $dateTime): bool
    {
        foreach ($this->objects as $existingEventStep) {
            if ($existingEventStep->eventId()->equals($eventId) && $existingEventStep->dateTime()->equals($dateTime)) {
                return true;
            }
        }

        return false;
    }
}