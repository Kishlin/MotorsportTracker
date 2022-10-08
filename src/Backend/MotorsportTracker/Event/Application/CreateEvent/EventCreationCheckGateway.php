<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;

interface EventCreationCheckGateway
{
    public function seasonHasEventWithIndexOrVenue(EventSeasonId $seasonId, EventIndex $index, EventLabel $label): bool;
}
