<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewType;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractStringType;

final class CalendarEventStepViewTypeType extends AbstractStringType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewType::class;
    }
}