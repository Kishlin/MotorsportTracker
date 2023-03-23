<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchRaceLapGateway
{
    public function findForEntryAndLap(UuidValueObject $entry, PositiveIntValueObject $lap): ?UuidValueObject;
}
