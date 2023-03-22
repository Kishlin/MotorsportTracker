<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\SaveDriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\SearchDriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\DriverByNameGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
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
final class DriverRepositorySpy extends AbstractRepositorySpy implements SaveDriverGateway, SearchDriverGateway, DriverByNameGateway
{
    public function __construct(
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(Driver $driver): void
    {
        if (false === $this->countryRepositorySpy->has($driver->countryId())
            || null !== $this->find($driver->name())
            || $this->refIsAlreadyTaken($driver)) {
            throw new DriverCreationFailureException();
        }

        $this->objects[$driver->id()->value()] = $driver;
    }

    public function findByNameOrRef(StringValueObject $name, NullableUuidValueObject $ref): ?UuidValueObject
    {
        foreach ($this->objects as $savedDriver) {
            if ($savedDriver->name()->equals($name) && $savedDriver->ref()->equals($ref)) {
                return $savedDriver->id();
            }
        }

        return null;
    }

    public function find(StringValueObject $name): ?UuidValueObject
    {
        foreach ($this->objects as $savedDriver) {
            if ($savedDriver->name()->equals($name)) {
                return $savedDriver->id();
            }
        }

        return null;
    }

    private function refIsAlreadyTaken(Driver $driver): bool
    {
        if (null === $driver->ref()->value()) {
            return false;
        }

        foreach ($this->objects as $savedDriver) {
            if ($savedDriver->ref()->equals($driver->ref())) {
                return true;
            }
        }

        return false;
    }
}
