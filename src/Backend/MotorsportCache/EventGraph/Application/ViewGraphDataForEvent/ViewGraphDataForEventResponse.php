<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewGraphDataForEventResponse implements Response
{
    private function __construct(
        private readonly GraphDataForEventJsonableView $view,
    ) {}

    public function view(): GraphDataForEventJsonableView
    {
        return $this->view;
    }

    public static function withView(GraphDataForEventJsonableView $view): self
    {
        return new self($view);
    }
}
