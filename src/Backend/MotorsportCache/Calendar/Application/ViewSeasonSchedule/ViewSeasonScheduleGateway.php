<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;

interface ViewSeasonScheduleGateway
{
    public function viewSchedule(string $championship, int $year): JsonableEventsView;
}
