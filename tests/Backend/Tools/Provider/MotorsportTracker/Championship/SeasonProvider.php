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

    public static function formulaOne2022(): Season
    {
        return Season::instance(
            new SeasonId('01dd2498-e231-4f34-82de-bf61153abbc4'),
            new SeasonYear(2022),
            new SeasonChampionshipId('9af4082a-0de2-4a8d-bd30-ec5cad0b26ed'),
        );
    }
}
