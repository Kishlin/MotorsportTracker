<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewSeasonEventsResponse implements Response
{
    private function __construct(
        private readonly SeasonEventsJsonableView $view,
    ) {
    }

    public function view(): SeasonEventsJsonableView
    {
        return $this->view;
    }

    public static function withView(SeasonEventsJsonableView $view): self
    {
        return new self($view);
    }
}
