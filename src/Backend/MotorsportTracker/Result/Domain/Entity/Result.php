<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ResultCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultEventStepId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultRacerId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Result extends AggregateRoot
{
    public function __construct(
        private ResultId $id,
        private ResultRacerId $racerId,
        private ResultEventStepId $eventStepId,
        private ResultPosition $position,
        private ResultPoints $points,
    ) {
    }

    public static function create(
        ResultId $id,
        ResultRacerId $racerId,
        ResultEventStepId $eventStepId,
        ResultPosition $position,
        ResultPoints $points,
    ): self {
        $result = new self($id, $racerId, $eventStepId, $position, $points);

        $result->record(new ResultCreatedDomainEvent($id));

        return $result;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        ResultId $id,
        ResultRacerId $racerId,
        ResultEventStepId $eventStepId,
        ResultPosition $position,
        ResultPoints $points,
    ): self {
        return new self($id, $racerId, $eventStepId, $position, $points);
    }

    public function id(): ResultId
    {
        return $this->id;
    }

    public function racerId(): ResultRacerId
    {
        return $this->racerId;
    }

    public function eventStepId(): ResultEventStepId
    {
        return $this->eventStepId;
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
