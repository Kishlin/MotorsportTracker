<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface EventIdOfEventStepIdReader
{
    public function eventIdForEventStepId(UuidValueObject $eventStepId): string;
}
