<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchDriverGateway
{
    public function search(string $name): ?UuidValueObject;
}
