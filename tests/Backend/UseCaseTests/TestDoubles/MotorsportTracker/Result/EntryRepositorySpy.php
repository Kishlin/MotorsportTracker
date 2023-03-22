<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\EntryCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\SaveEntryGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\SearchEntryGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Gateway\FindEntryForSessionAndNumberGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Entry[] $objects
 *
 * @method Entry[]    all()
 * @method null|Entry get(UuidValueObject $id)
 * @method Entry      safeGet(UuidValueObject $id)
 */
final class EntryRepositorySpy extends AbstractRepositorySpy implements SaveEntryGateway, SearchEntryGateway, FindEntryForSessionAndNumberGateway
{
    public function save(Entry $entry): void
    {
        if ($this->isADuplicateOnSession($entry)) {
            throw new EntryCreationFailureException();
        }

        $this->add($entry);
    }

    public function find(
        UuidValueObject $session,
        UuidValueObject $driver,
        UuidValueObject $team,
        PositiveIntValueObject $carNumber,
    ): ?UuidValueObject {
        foreach ($this->objects as $savedEntry) {
            if ($savedEntry->team()->equals($team)
                && $savedEntry->driver()->equals($driver)
                && $savedEntry->session()->equals($session)
                && $savedEntry->carNumber()->equals($carNumber)
            ) {
                return $savedEntry->id();
            }
        }

        return null;
    }

    public function findForSessionAndNumber(UuidValueObject $session, PositiveIntValueObject $number): ?UuidValueObject
    {
        foreach ($this->objects as $savedEntry) {
            if ($savedEntry->session()->equals($session) && $savedEntry->carNumber()->equals($number)) {
                return $savedEntry->id();
            }
        }

        return null;
    }

    private function isADuplicateOnSession(Entry $entry): bool
    {
        foreach ($this->objects as $savedEntry) {
            if ($savedEntry->session()->equals($entry->session())
                && (
                    $savedEntry->driver()->equals($entry->driver())
                    || $savedEntry->carNumber()->equals($entry->carNumber())
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
