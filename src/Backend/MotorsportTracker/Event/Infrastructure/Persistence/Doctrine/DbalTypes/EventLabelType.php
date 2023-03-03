<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class EventLabelType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return EventLabel::class;
    }
}
