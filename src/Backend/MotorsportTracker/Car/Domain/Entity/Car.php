<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Car\Domain\DomainEvent\CarCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Car extends AggregateRoot
{
    private function __construct(
        private CarId $carId,
        private CarTeamId $teamId,
        private CarSeasonId $seasonId,
        private CarNumber $number,
    ) {
    }

    public static function create(CarId $id, CarTeamId $teamId, CarSeasonId $seasonId, CarNumber $number): self
    {
        $car = new self($id, $teamId, $seasonId, $number);

        $car->record(new CarCreatedDomainEvent($id));

        return $car;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(CarId $id, CarTeamId $teamId, CarSeasonId $seasonId, CarNumber $number): self
    {
        return new self($id, $teamId, $seasonId, $number);
    }

    public function carId(): CarId
    {
        return $this->carId;
    }

    public function teamId(): CarTeamId
    {
        return $this->teamId;
    }

    public function seasonId(): CarSeasonId
    {
        return $this->seasonId;
    }

    public function number(): CarNumber
    {
        return $this->number;
    }
}
