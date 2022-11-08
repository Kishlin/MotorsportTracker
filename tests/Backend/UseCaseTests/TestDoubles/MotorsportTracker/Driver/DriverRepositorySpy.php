<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverViewer;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Gateway\DriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\CountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Driver[] $objects
 *
 * @method Driver[]    all()
 * @method null|Driver get(DriverId $id)
 */
final class DriverRepositorySpy extends AbstractRepositorySpy implements DriverGateway, SearchDriverViewer
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

    public function search(string $name): ?DriverId
    {
        foreach ($this->objects as $driver) {
            if (str_contains("{$driver->firstname()->value()} {$driver->name()->value()}", $name)) {
                return $driver->id();
            }
        }
        
        return null;
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
