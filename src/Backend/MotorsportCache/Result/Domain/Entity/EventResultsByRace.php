<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Domain\Entity;

use JsonException;
use Kishlin\Backend\MotorsportCache\Result\Domain\DomainEvent\EventResultsByRaceCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsByRaceValueObject;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class EventResultsByRace extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $event,
        private readonly ResultsByRaceValueObject $resultsByRace,
    ) {
    }

    public static function create(UuidValueObject $id, UuidValueObject $event, ResultsByRaceValueObject $resultsByRace): self
    {
        $eventResultsByRace = new self($id, $event, $resultsByRace);

        $eventResultsByRace->record(new EventResultsByRaceCreatedDomainEvent($id));

        return $eventResultsByRace;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, UuidValueObject $event, ResultsByRaceValueObject $resultsByRace): self
    {
        return new self($id, $event, $resultsByRace);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function event(): UuidValueObject
    {
        return $this->event;
    }

    public function resultsByRace(): ResultsByRaceValueObject
    {
        return $this->resultsByRace;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'              => $this->id->value(),
            'event'           => $this->event->value(),
            'results_by_race' => $this->resultsByRace->asString(),
        ];
    }
}
