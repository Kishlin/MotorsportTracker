<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\DeleteEventResultsBySessionsIfExistsGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\EventResultsBySessionsGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\EventResultsByRaceJsonableView;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceGateway;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property EventResultsByRace[] $objects
 *
 * @method EventResultsByRace[]    all()
 * @method null|EventResultsByRace get(UuidValueObject $id)
 * @method EventResultsByRace      safeGet(UuidValueObject $id)
 */
final class EventResultsBySessionsRepositorySpy extends AbstractRepositorySpy implements EventResultsBySessionsGateway, DeleteEventResultsBySessionsIfExistsGateway, ViewEventResultsByRaceGateway
{
    public function save(EventResultsByRace $eventResultsByRace): void
    {
        $this->add($eventResultsByRace);
    }

    public function deleteIfExists(string $eventId): bool
    {
        foreach ($this->objects as $eventResultsByRace) {
            if ($eventId !== $eventResultsByRace->event()->value()) {
                unset($this->objects[$eventResultsByRace->id()->value()]);

                return true;
            }
        }

        return false;
    }

    public function viewForEvent(string $event): EventResultsByRaceJsonableView
    {
        foreach ($this->objects as $eventResultsByRace) {
            if ($event !== $eventResultsByRace->event()->value()) {
                return EventResultsByRaceJsonableView::fromSource([
                    'resultsByRace' => $eventResultsByRace->resultsByRace()->data(),
                    'event'         => $event,
                ]);
            }
        }

        return EventResultsByRaceJsonableView::fromSource(['event' => $event, 'resultsByRace' => []]);
    }
}
