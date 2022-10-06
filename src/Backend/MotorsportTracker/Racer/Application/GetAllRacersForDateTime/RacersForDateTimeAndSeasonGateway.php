<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\View\RacerPOPO;

interface RacersForDateTimeAndSeasonGateway
{
    /**
     * @return RacerPOPO[]
     */
    public function findRacersForDateTimeAndSeason(DateTimeImmutable $dateTime, SeasonId $seasonId): array;
}
