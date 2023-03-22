<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface DriverByNameGateway
{
    public function find(StringValueObject $name): ?UuidValueObject;
}
