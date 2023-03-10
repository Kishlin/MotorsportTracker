<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;

interface SaveEntryGateway
{
    public function save(Entry $entry): void;
}
