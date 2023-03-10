<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\EntryList;

use Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists\SaveEntryGateway;
use Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists\SearchEntryGateway;
use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use RuntimeException;

/**
 * @property Entry[] $objects
 *
 * @method Entry[]    all()
 * @method null|Entry get(UuidValueObject $id)
 * @method Entry      safeGet(UuidValueObject $id)
 */
final class EntryRepositorySpy extends AbstractRepositorySpy implements SaveEntryGateway, SearchEntryGateway
{
    public function save(Entry $entry): void
    {
        if ($this->isADuplicateEntry($entry)) {
            throw new RuntimeException('Duplicate.');
        }

        $this->add($entry);
    }

    public function find(UuidValueObject $event, UuidValueObject $driver, StringValueObject $carNumber): ?UuidValueObject
    {
        foreach ($this->objects as $savedEntry) {
            if ($savedEntry->event()->equals($event)
                && $savedEntry->driver()->equals($driver)
                && $savedEntry->carNumber()->equals($carNumber)) {
                return $savedEntry->id();
            }
        }

        return null;
    }

    public function isADuplicateEntry(Entry $entry): bool
    {
        foreach ($this->objects as $savedEntry) {
            if ($savedEntry->id()->equals($entry->id())
                && $savedEntry->event()->equals($entry->event())
                && $savedEntry->driver()->equals($entry->driver())
                && $savedEntry->carNumber()->equals($entry->carNumber())) {
                return true;
            }
        }

        return false;
    }
}
