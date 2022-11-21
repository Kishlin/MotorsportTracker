<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\EventCreationCheckGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\EventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Event[] $objects
 *
 * @method Event[]    all()
 * @method null|Event get(UuidValueObject $id)
 * @method Event      safeGet(UuidValueObject $id)
 */
final class EventRepositorySpy extends AbstractRepositorySpy implements EventGateway, EventCreationCheckGateway
{
    /**
     * @throws Exception
     */
    public function save(Event $event): void
    {
        if ($this->seasonHasEventWithIndexOrVenue($event->seasonId(), $event->index(), $event->label())) {
            throw new Exception();
        }

        $this->objects[$event->id()->value()] = $event;
    }

    public function seasonHasEventWithIndexOrVenue(EventSeasonId $seasonId, EventIndex $index, EventLabel $label): bool
    {
        foreach ($this->objects as $savedEvent) {
            if (false === $savedEvent->seasonId()->equals($seasonId)) {
                continue;
            }

            if ($savedEvent->index()->equals($index) || $savedEvent->label()->equals($label)) {
                return true;
            }
        }

        return false;
    }
}
