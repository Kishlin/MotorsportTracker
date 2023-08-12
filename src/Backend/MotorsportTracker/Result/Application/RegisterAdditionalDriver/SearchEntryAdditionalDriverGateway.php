<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchEntryAdditionalDriverGateway
{
    public function find(UuidValueObject $entry, UuidValueObject $driver): ?UuidValueObject;
}
