<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

interface CalendarGateway
{
    public function fetch(string $seasonRef): CalendarResponse;
}
