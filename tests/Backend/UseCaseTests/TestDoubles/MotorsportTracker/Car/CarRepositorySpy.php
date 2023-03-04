<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\CarRegistrationFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\CarGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Car[] $objects
 *
 * @method Car[]    all()
 * @method null|Car get(UuidValueObject $id)
 * @method Car      safeGet(UuidValueObject $id)
 */
final class CarRepositorySpy extends AbstractRepositorySpy implements CarGateway
{
    public function __construct(
        private TeamRepositorySpy $teamRepositorySpy,
        private SaveSeasonRepositorySpy $seasonRepositorySpy,
    ) {
    }

    public function save(Car $car): void
    {
        if ($this->numberIsAlreadyTaken($car)
            || false === $this->teamRepositorySpy->has($car->teamId())
            || false === $this->seasonRepositorySpy->has($car->seasonId())) {
            throw new CarRegistrationFailureException();
        }

        $this->objects[$car->id()->value()] = $car;
    }

    private function numberIsAlreadyTaken(Car $car): bool
    {
        foreach ($this->objects as $savedCar) {
            if ($savedCar->number()->equals($car->number())
                && $savedCar->teamId()->equals($car->teamId())
                && $savedCar->seasonId()->equals($car->seasonId())) {
                return true;
            }
        }

        return false;
    }
}
