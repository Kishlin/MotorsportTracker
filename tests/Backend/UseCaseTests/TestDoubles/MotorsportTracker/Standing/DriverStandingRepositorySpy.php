<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\DriverStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property DriverStanding[] $objects
 *
 * @method DriverStanding[]    all()
 * @method null|DriverStanding get(DriverStandingId $id)
 */
final class DriverStandingRepositorySpy extends AbstractRepositorySpy implements DriverStandingGateway
{
    public function save(DriverStanding $driverStanding): void
    {
        $uniqueKey = $driverStanding->eventId()->value() . '-' . $driverStanding->driverId()->value();

        $this->objects[$uniqueKey] = $driverStanding;
    }
}
