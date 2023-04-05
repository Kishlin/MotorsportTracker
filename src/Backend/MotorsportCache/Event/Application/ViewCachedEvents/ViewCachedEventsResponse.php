<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCachedEventsResponse implements Response
{
    private function __construct(
        private readonly CachedEventsJsonableView $events,
    ) {
    }

    public function events(): CachedEventsJsonableView
    {
        return $this->events;
    }

    public static function withView(CachedEventsJsonableView $view): self
    {
        return new self($view);
    }
}
