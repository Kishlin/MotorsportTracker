<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\EventResultsByRaceGateway;
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
final class EventResultsByRaceRepositorySpy extends AbstractRepositorySpy implements EventResultsByRaceGateway
{
    public function save(EventResultsByRace $eventResultsByRace): void
    {
        $this->add($eventResultsByRace);
    }
}
