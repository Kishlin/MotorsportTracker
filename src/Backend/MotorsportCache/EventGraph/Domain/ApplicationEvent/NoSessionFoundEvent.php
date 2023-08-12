<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent;

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
