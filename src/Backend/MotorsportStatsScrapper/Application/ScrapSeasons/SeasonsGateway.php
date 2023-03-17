<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons;

interface SeasonsGateway
{
    public function fetchForChampionship(string $championshipUuid): SeasonsResponse;
}
