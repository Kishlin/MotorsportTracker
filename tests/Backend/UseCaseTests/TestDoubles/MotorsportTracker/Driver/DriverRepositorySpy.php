<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\SaveDriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\SearchDriverGateway;
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
final class DriverRepositorySpy extends AbstractRepositorySpy implements SaveDriverGateway, SearchDriverGateway
{
    public function __construct(
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(Driver $driver): void
    {
        if (false === $this->countryRepositorySpy->has($driver->countryId())
            || $this->nameIsAlreadyTaken($driver)
            || $this->slugIsAlreadyTaken($driver)) {
            throw new DriverCreationFailureException();
        }

        $this->objects[$driver->id()->value()] = $driver;
    }

    public function findBySlug(string $slug): ?UuidValueObject
    {
        foreach ($this->objects as $savedDriver) {
            if ($slug === $savedDriver->slug()->value()) {
                return $savedDriver->id();
            }
        }

        return null;
    }

    private function slugIsAlreadyTaken(Driver $driver): bool
    {
        foreach ($this->objects as $savedDriver) {
            if ($savedDriver->slug()->equals($driver->slug())) {
                return true;
            }
        }

        return false;
    }

    private function nameIsAlreadyTaken(Driver $driver): bool
    {
        foreach ($this->objects as $savedDriver) {
            if ($savedDriver->name()->equals($driver->name())) {
                return true;
            }
        }

        return false;
    }
}
