<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarTeamId;

final class CarProvider
{
    public static function redBullRacing2022FirstCar(): Car
    {
        return Car::instance(
            new CarId('c2ba96dc-eef3-4b7b-a06c-718f044206a9'),
            new CarTeamId('aee290c9-3ab0-4a9a-a707-8756f9a7760f'),
            new CarSeasonId('01dd2498-e231-4f34-82de-bf61153abbc4'),
            new CarNumber(1),
        );
    }
}
