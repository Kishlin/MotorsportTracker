<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Gateway\DriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\CountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Driver[] $objects
 *
 * @method Driver get(DriverId $id)
 */
final class DriverRepositorySpy extends AbstractRepositorySpy implements DriverGateway
{
    public function __construct(
        private CountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(Driver $driver): void
    {
        if (false === $this->countryRepositorySpy->has($driver->countryId())
            || $this->firstnameAndNameIsAlreadyTaken($driver)) {
            throw new DriverCreationFailureException();
        }

        $this->objects[$driver->id()->value()] = $driver;
    }

    private function firstnameAndNameIsAlreadyTaken(Driver $driver): bool
    {
        foreach ($this->objects as $savedDriver) {
            if ($savedDriver->firstname()->equals($driver->firstname())
                || $savedDriver->name()->equals($driver->name())) {
                return true;
            }
        }

        return false;
    }
}
