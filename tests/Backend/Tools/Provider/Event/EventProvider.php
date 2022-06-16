<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\Event;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;

final class EventProvider
{
    public static function dutchGrandPrix(): Event
    {
        return Event::instance(
            new EventId('84b3e2e0-0f81-4747-be83-bcbf958b7105'),
            new EventSeasonId('01dd2498-e231-4f34-82de-bf61153abbc4'),
            new EventVenueId('ce924d04-cc00-4dbd-95ef-1b8b4e1a41f7'),
            new EventIndex(16),
            new EventLabel('Dutch GP'),
        );
    }
}
