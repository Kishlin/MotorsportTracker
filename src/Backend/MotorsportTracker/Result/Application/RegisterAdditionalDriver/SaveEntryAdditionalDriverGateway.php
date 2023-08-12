<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\EntryAdditionalDriver;

interface SaveEntryAdditionalDriverGateway
{
    public function saveEntryAdditionalDriver(EntryAdditionalDriver $entryAdditionalDriver): void;
}
