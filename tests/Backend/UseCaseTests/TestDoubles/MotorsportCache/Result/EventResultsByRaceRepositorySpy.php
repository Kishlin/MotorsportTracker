<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\DeleteEventResultsByRaceIfExistsGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\EventResultsByRaceGateway;
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
final class EventResultsByRaceRepositorySpy extends AbstractRepositorySpy implements EventResultsByRaceGateway, DeleteEventResultsByRaceIfExistsGateway
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
}
