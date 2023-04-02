<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents;

interface ViewSeasonEventsGateway
{
    public function viewForSeason(string $championshipSlug, int $year): SeasonEventsJsonableView;
}
