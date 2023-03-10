<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchEntryGateway
{
    public function find(UuidValueObject $event, UuidValueObject $driver, StringValueObject $carNumber): ?UuidValueObject;
}
