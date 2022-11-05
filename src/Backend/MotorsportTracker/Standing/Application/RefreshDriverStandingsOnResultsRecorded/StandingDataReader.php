<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded;

interface StandingDataReader
{
    /**
     * @return StandingDataDTO[]
     */
    public function findStandingDataForEvent(string $eventId): array;
}
