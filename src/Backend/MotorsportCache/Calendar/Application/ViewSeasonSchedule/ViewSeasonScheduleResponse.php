<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewSeasonScheduleResponse implements Response
{
    private function __construct(
        private readonly JsonableEventsView $view,
    ) {
    }

    public function schedule(): JsonableEventsView
    {
        return $this->view;
    }

    public static function withView(JsonableEventsView $view): self
    {
        return new self($view);
    }
}
