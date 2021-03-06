<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverCountryId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverFirstname;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;

final class DriverProvider
{
    public static function dutchDriver(): Driver
    {
        return Driver::instance(
            new DriverId('09781b37-55d1-4107-a9b0-2b86b2baabef'),
            new DriverFirstname('Max'),
            new DriverName('Verstappen'),
            new DriverCountryId('32bc1722-2ed6-4a81-b1dd-0cf578027b1f'),
        );
    }
}
