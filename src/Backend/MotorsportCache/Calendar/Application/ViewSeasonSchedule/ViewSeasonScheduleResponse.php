<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewSeasonScheduleResponse implements Response
{
    private function __construct(
        private readonly JsonableScheduleView $view,
    ) {
    }

    public function schedule(): JsonableScheduleView
    {
        return $this->view;
    }

    public static function withView(JsonableScheduleView $view): self
    {
        return new self($view);
    }
}
