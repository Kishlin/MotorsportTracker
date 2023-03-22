<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\SaveEntryGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveEntryRepository extends CoreRepository implements SaveEntryGateway
{
    public function save(Entry $entry): void
    {
        $this->persist($entry);
    }
}
