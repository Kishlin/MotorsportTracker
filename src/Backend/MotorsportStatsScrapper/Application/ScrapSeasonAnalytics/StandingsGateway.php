<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics;

interface StandingsGateway
{
    public function fetch(string $seasonRef): StandingsResponse;
}
