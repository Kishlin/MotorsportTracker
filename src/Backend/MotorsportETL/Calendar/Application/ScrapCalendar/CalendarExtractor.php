<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

interface CalendarExtractor
{
    public function extract(SeasonIdentity $season): string;
}
