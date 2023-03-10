<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Infrastructure\Persistence\Doctrine\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists\SaveEntryGateway;
use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveEntryRepositoryUsingDoctrine extends CoreRepository implements SaveEntryGateway
{
    public function save(Entry $entry): void
    {
        $this->persist($entry);
    }
}
