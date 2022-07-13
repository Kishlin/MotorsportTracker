<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveCarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;

final class DriverMoveProvider
{
    public static function verstappenAtRedBullRacingIn2022(): DriverMove
    {
        return DriverMove::instance(
            new DriverMoveId('fde53488-7081-4c65-8966-0523a1144b76'),
            new DriverMoveDriverId('09781b37-55d1-4107-a9b0-2b86b2baabef'),
            new DriverMoveCarId('c2ba96dc-eef3-4b7b-a06c-718f044206a9'),
            new DriverMoveDate(new DateTimeImmutable('2022-01-01')),
        );
    }
}
