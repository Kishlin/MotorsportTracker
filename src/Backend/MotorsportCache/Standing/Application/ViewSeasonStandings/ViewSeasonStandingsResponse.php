<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final readonly class ViewSeasonStandingsResponse implements Response
{
    private function __construct(
        private SeasonStandingsJsonableView $view,
    ) {
    }

    public function view(): SeasonStandingsJsonableView
    {
        return $this->view;
    }

    public static function withView(SeasonStandingsJsonableView $view): self
    {
        return new self($view);
    }
}
