<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\DriverParticipationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\DriverParticipationDriverId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\DriverParticipationId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\DriverParticipationSeasonId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class DriverParticipation extends AggregateRoot
{
    private function __construct(
        private DriverParticipationId $id,
        private DriverParticipationSeasonId $seasonId,
        private DriverParticipationDriverId $driverId,
    ) {
    }

    public static function create(
        DriverParticipationId $id,
        DriverParticipationSeasonId $seasonId,
        DriverParticipationDriverId $driverId,
    ): self {
        $driverParticipation = new self($id, $seasonId, $driverId);

        $driverParticipation->record(new DriverParticipationCreatedDomainEvent($id));

        return $driverParticipation;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        DriverParticipationId $id,
        DriverParticipationSeasonId $seasonId,
        DriverParticipationDriverId $driverId,
    ): self {
        return new self($id, $seasonId, $driverId);
    }

    public function id(): DriverParticipationId
    {
        return $this->id;
    }

    public function seasonId(): DriverParticipationSeasonId
    {
        return $this->seasonId;
    }

    public function driverId(): DriverParticipationDriverId
    {
        return $this->driverId;
    }
}
