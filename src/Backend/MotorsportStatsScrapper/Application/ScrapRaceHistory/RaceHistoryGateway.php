<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

interface RaceHistoryGateway
{
    public function fetch(string $sessionRef): RaceHistoryResponse;
}
