<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewEventResultsByRaceResponse implements Response
{
    private function __construct(
        private readonly EventResultsByRaceJsonableView $view,
    ) {
    }

    public function view(): EventResultsByRaceJsonableView
    {
        return $this->view;
    }

    public static function withView(EventResultsByRaceJsonableView $view): self
    {
        return new self($view);
    }
}
