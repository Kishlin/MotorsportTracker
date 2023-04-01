<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\DeleteSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SaveSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\SeasonEvents;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property SeasonEvents[] $objects
 *
 * @method SeasonEvents[]    all()
 * @method null|SeasonEvents get(UuidValueObject $id)
 * @method SeasonEvents      safeGet(UuidValueObject $id)
 */
final class SeasonEventsRepositorySpy extends AbstractRepositorySpy implements SaveSeasonEventsGateway, DeleteSeasonEventsGateway
{
    public function save(SeasonEvents $seasonEvents): void
    {
        $this->add($seasonEvents);
    }

    public function deleteIfExists(StringValueObject $championship, StrictlyPositiveIntValueObject $year): bool
    {
        $toDelete = $this->find($championship, $year);
        if (null === $toDelete) {
            return false;
        }

        unset($this->objects[$toDelete->id()->value()]);

        return true;
    }

    public function find(StringValueObject $championship, StrictlyPositiveIntValueObject $year): ?SeasonEvents
    {
        foreach ($this->objects as $seasonEvents) {
            if ($seasonEvents->championship()->equals($championship) && $seasonEvents->year()->equals($year)) {
                return $seasonEvents;
            }
        }

        return null;
    }
}
