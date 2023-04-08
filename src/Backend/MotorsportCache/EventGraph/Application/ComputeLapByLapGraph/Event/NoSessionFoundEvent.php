<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class NoSessionFoundEvent implements ApplicationEvent
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}
