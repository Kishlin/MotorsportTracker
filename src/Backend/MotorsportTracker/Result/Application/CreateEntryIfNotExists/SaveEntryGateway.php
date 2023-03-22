<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;

interface SaveEntryGateway
{
    public function save(Entry $entry): void;
}
