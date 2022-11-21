<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\DriverStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property DriverStanding[] $objects
 *
 * @method DriverStanding[]    all()
 * @method null|DriverStanding get(UuidValueObject $id)
 * @method DriverStanding      safeGet(UuidValueObject $id)
 */
final class DriverStandingRepositorySpy extends AbstractRepositorySpy implements DriverStandingGateway
{
    public function save(DriverStanding $driverStanding): void
    {
        $uniqueKey = $driverStanding->eventId()->value() . '-' . $driverStanding->driverId()->value();

        $this->objects[$uniqueKey] = $driverStanding;
    }

    public function find(DriverStandingDriverId $driverId, DriverStandingEventId $eventId): ?DriverStanding
    {
        foreach ($this->objects as $driverStanding) {
            if ($driverId->equals($driverStanding->driverId()) && $eventId->equals($driverStanding->eventId())) {
                return $driverStanding;
            }
        }

        return null;
    }
}
