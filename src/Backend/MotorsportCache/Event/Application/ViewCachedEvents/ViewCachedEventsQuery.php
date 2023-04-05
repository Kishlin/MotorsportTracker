<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewCachedEventsQuery implements Query
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}
