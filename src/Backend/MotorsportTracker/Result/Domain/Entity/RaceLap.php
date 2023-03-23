<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use JsonException;
use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\RaceLapCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class RaceLap extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $entry,
        private readonly StrictlyPositiveIntValueObject $lap,
        private readonly StrictlyPositiveIntValueObject $position,
        private readonly BoolValueObject $pit,
        private readonly StrictlyPositiveIntValueObject $time,
        private readonly NullableIntValueObject $timeToLead,
        private readonly NullableIntValueObject $lapsToLead,
        private readonly NullableIntValueObject $timeToNext,
        private readonly NullableIntValueObject $lapsToNext,
        private readonly TyreDetailsValueObject $tyreDetails,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $entry,
        StrictlyPositiveIntValueObject $lap,
        StrictlyPositiveIntValueObject $position,
        BoolValueObject $pit,
        StrictlyPositiveIntValueObject $time,
        NullableIntValueObject $timeToLead,
        NullableIntValueObject $lapsToLead,
        NullableIntValueObject $timeToNext,
        NullableIntValueObject $lapsToNext,
        TyreDetailsValueObject $tyreDetails,
    ): self {
        $raceHistoryLap = new self(
            $id,
            $entry,
            $lap,
            $position,
            $pit,
            $time,
            $timeToLead,
            $lapsToLead,
            $timeToNext,
            $lapsToNext,
            $tyreDetails,
        );

        $raceHistoryLap->record(new RaceLapCreatedDomainEvent($id));

        return $raceHistoryLap;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $entry,
        StrictlyPositiveIntValueObject $lap,
        StrictlyPositiveIntValueObject $position,
        BoolValueObject $pit,
        StrictlyPositiveIntValueObject $time,
        NullableIntValueObject $timeToLead,
        NullableIntValueObject $lapsToLead,
        NullableIntValueObject $timeToNext,
        NullableIntValueObject $lapsToNext,
        TyreDetailsValueObject $tyreDetails,
    ): self {
        return new self(
            $id,
            $entry,
            $lap,
            $position,
            $pit,
            $time,
            $timeToLead,
            $lapsToLead,
            $timeToNext,
            $lapsToNext,
            $tyreDetails,
        );
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function entry(): UuidValueObject
    {
        return $this->entry;
    }

    public function lap(): StrictlyPositiveIntValueObject
    {
        return $this->lap;
    }

    public function position(): StrictlyPositiveIntValueObject
    {
        return $this->position;
    }

    public function pit(): BoolValueObject
    {
        return $this->pit;
    }

    public function time(): StrictlyPositiveIntValueObject
    {
        return $this->time;
    }

    public function timeToLead(): NullableIntValueObject
    {
        return $this->timeToLead;
    }

    public function lapsToLead(): NullableIntValueObject
    {
        return $this->lapsToLead;
    }

    public function timeToNext(): NullableIntValueObject
    {
        return $this->timeToNext;
    }

    public function lapsToNext(): NullableIntValueObject
    {
        return $this->lapsToNext;
    }

    public function tyreDetails(): TyreDetailsValueObject
    {
        return $this->tyreDetails;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'           => $this->id->value(),
            'entry'        => $this->entry->value(),
            'lap'          => $this->lap->value(),
            'position'     => $this->position->value(),
            'pit'          => $this->pit->value() ? 1 : 0,
            'time'         => $this->time->value(),
            'time_to_lead' => $this->timeToLead->value(),
            'laps_to_lead' => $this->lapsToLead->value(),
            'time_to_next' => $this->timeToNext->value(),
            'laps_to_next' => $this->lapsToNext->value(),
            'tyre_details' => $this->tyreDetails->asString(),
        ];
    }
}
