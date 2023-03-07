<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class SeriesIsMissingApplicationEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $seriesSlug,
    ) {
    }

    public function seriesSlug(): string
    {
        return $this->seriesSlug;
    }

    public static function forSlug(string $seriesSlug): self
    {
        return new self($seriesSlug);
    }
}
