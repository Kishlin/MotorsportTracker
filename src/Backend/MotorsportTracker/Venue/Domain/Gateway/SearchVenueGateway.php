<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Domain\Gateway;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchVenueGateway
{
    public function search(string $slug): ?UuidValueObject;
}
