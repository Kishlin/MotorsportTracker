<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\DomainEvent\RacerCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Racer extends AggregateRoot
{
    private function __construct(
        private RacerId $id,
        private RacerDriverId $driverId,
        private RacerCarId $carId,
        private RacerStartDate $startDate,
        private RacerEndDate $endDate,
    ) {
    }

    public static function create(
        RacerId $id,
        RacerDriverId $driverId,
        RacerCarId $carId,
        RacerStartDate $startDate,
        RacerEndDate $endDate,
    ): self {
        $racer = new self($id, $driverId, $carId, $startDate, $endDate);

        $racer->record(new RacerCreatedDomainEvent($id));

        return $racer;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        RacerId $id,
        RacerDriverId $driverId,
        RacerCarId $carId,
        RacerStartDate $startDate,
        RacerEndDate $endDate,
    ): self {
        return new self($id, $driverId, $carId, $startDate, $endDate);
    }

    public function id(): RacerId
    {
        return $this->id;
    }

    public function driverId(): RacerDriverId
    {
        return $this->driverId;
    }

    public function carId(): RacerCarId
    {
        return $this->carId;
    }

    public function startDate(): RacerStartDate
    {
        return $this->startDate;
    }

    public function endDate(): RacerEndDate
    {
        return $this->endDate;
    }
}
