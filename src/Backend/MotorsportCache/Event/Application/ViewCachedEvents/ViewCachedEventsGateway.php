<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents;

interface ViewCachedEventsGateway
{
    public function findAll(): CachedEventsJsonableView;
}
