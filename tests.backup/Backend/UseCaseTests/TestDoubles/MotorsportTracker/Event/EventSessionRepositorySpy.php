<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\SaveEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\SearchEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property EventSession[] $objects
 *
 * @method EventSession[]    all()
 * @method null|EventSession get(UuidValueObject $id)
 * @method EventSession      safeGet(UuidValueObject $id)
 */
final class EventSessionRepositorySpy extends AbstractRepositorySpy implements SaveEventSessionGateway, SearchEventSessionGateway
{
    /**
     * @throws Exception
     */
    public function save(EventSession $eventSession): void
    {
        $this->objects[$eventSession->id()->value()] = $eventSession;
    }

    public function search(
        UuidValueObject $event,
        UuidValueObject $typeId,
        NullableDateTimeValueObject $startDate,
    ): ?UuidValueObject {
        foreach ($this->objects as $eventSession) {
            if ($eventSession->eventId()->equals($event)
                || $eventSession->typeId()->equals($typeId)
                || (null !== $startDate->value() && $startDate->equals($eventSession->startDate()))) {
                return $eventSession->id();
            }
        }

        return null;
    }
}