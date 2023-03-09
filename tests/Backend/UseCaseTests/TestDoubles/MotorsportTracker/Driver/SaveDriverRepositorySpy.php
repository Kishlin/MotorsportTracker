<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\SaveDriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Driver[] $objects
 *
 * @method Driver[]    all()
 * @method null|Driver get(UuidValueObject $id)
 * @method Driver      safeGet(UuidValueObject $id)
 */
final class SaveDriverRepositorySpy extends AbstractRepositorySpy implements SaveDriverGateway
{
    public function __construct(
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
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
            if ($savedDriver->name()->equals($driver->name())) {
                return true;
            }
        }

        return false;
    }
}
