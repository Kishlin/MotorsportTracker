<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule;

interface ViewSeasonScheduleGateway
{
    public function viewSchedule(string $championship, int $year): JsonableScheduleView;
}
