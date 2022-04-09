<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SeasonProvider
{
    public static function forChampionship(UuidValueObject $championshipId): Season
    {
        return Season::instance(
            new SeasonId('1fa6b778-7fe5-499d-93ec-d38d0b77e1d6'),
            new SeasonYear(1993),
            SeasonChampionshipId::fromOther($championshipId),
        );
    }
}
