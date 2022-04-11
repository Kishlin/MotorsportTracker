<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ResultCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultDriverId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultEventStepId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultFastestLapTime;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Result extends AggregateRoot
{
    public function __construct(
        private ResultId $id,
        private ResultTeamId $teamId,
        private ResultDriverId $driverId,
        private ResultEventStepId $eventStepId,
        private ResultFastestLapTime $fastestLapTime,
        private ResultPosition $position,
        private ResultPoints $points,
    ) {
    }

    public static function create(
        ResultId $id,
        ResultTeamId $teamId,
        ResultDriverId $driverId,
        ResultEventStepId $eventStepId,
        ResultFastestLapTime $fastestLapTime,
        ResultPosition $position,
        ResultPoints $points,
    ): self {
        $result = new self($id, $teamId, $driverId, $eventStepId, $fastestLapTime, $position, $points);

        $result->record(new ResultCreatedDomainEvent($id));

        return $result;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        ResultId $id,
        ResultTeamId $teamId,
        ResultDriverId $driverId,
        ResultEventStepId $eventStepId,
        ResultFastestLapTime $fastestLapTime,
        ResultPosition $position,
        ResultPoints $points,
    ): self {
        return new self($id, $teamId, $driverId, $eventStepId, $fastestLapTime, $position, $points);
    }

    public function id(): ResultId
    {
        return $this->id;
    }

    public function teamId(): ResultTeamId
    {
        return $this->teamId;
    }

    public function driverId(): ResultDriverId
    {
        return $this->driverId;
    }

    public function eventStepId(): ResultEventStepId
    {
        return $this->eventStepId;
    }

    public function fastestLapTime(): ResultFastestLapTime
    {
        return $this->fastestLapTime;
    }

    public function position(): ResultPosition
    {
        return $this->position;
    }

    public function points(): ResultPoints
    {
        return $this->points;
    }
}
