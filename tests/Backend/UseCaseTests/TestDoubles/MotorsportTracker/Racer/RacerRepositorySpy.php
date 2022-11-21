<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer;

use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\ExistingRacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Gateway\RacerGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\DriverMoveRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Racer[] $objects
 *
 * @method Racer[]    all()
 * @method null|Racer get(UuidValueObject $id)
 * @method Racer      safeGet(UuidValueObject $id)
 */
final class RacerRepositorySpy extends AbstractRepositorySpy implements RacerGateway, ExistingRacerGateway
{
    public function __construct(
        private DriverMoveRepositorySpy $driverMoveRepositorySpy,
    ) {
    }

    public function save(Racer $racer): void
    {
        $this->objects[$racer->id()->value()] = $racer;
    }

    public function findIfExistsForDriverMove(UuidValueObject $driverMoveId): ?Racer
    {
        $driverMove = $this->driverMoveRepositorySpy->safeGet($driverMoveId);

        foreach ($this->objects as $racer) {
            if (
                $racer->driverId()->equals($driverMove->driverId())
                && $racer->endDate()->value() >= $driverMove->date()->value()
                && $racer->startDate()->value() <= $driverMove->date()->value()
            ) {
                return $racer;
            }
        }

        return null;
    }

    public function hasOneForDriver(string $driverId): bool
    {
        foreach ($this->objects as $racer) {
            if ($racer->driverId()->value() === $driverId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Racer[]
     */
    public function findForDriverAndCar(string $driverId, string $carId): array
    {
        $driverIdFilter = static function (Racer $racer) use ($driverId, $carId) {
            return $racer->driverId()->value() === $driverId && $racer->carId()->value() === $carId;
        };

        return array_filter($this->objects, $driverIdFilter);
    }
}
