<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\ArrayValueObject;

final class StandingsViewEvents extends ArrayValueObject
{
    /**
     * @param string ...$events
     */
    public static function with(...$events): self
    {
        return new self($events);
    }
}
