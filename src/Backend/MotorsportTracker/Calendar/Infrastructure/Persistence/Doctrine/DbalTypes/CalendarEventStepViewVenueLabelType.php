<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewVenueLabel;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class CalendarEventStepViewVenueLabelType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewVenueLabel::class;
    }
}
