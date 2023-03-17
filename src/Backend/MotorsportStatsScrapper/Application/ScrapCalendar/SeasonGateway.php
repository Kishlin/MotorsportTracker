<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

interface SeasonGateway
{
    public function find(string $championshipName, int $year): ?SeasonDTO;
}
