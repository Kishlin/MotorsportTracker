<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Racer;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;

final class RacerProvider
{
    public static function verstappenToRedBullRacingIn2022(): Racer
    {
        return Racer::instance(
            new RacerId('87d171f5-0a25-4df9-9182-2f501ffbe6e9'),
            new RacerDriverId('09781b37-55d1-4107-a9b0-2b86b2baabef'),
            new RacerCarId('c2ba96dc-eef3-4b7b-a06c-718f044206a9'),
            new RacerStartDate(new DateTimeImmutable('2022-01-01')),
            new RacerEndDate(new DateTimeImmutable('2022-12-31')),
        );
    }
}
