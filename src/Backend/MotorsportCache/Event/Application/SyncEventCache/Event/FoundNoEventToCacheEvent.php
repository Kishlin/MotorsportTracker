<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class FoundNoEventToCacheEvent implements ApplicationEvent
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}
