<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason;

interface ConstructorStandingsGateway
{
    public function fetch(string $seasonRef): ConstructorStandingsResponse;
}
