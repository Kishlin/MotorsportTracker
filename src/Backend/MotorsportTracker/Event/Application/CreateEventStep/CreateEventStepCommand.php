<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateEventStepCommand implements Command
{
    private function __construct(
        private string $eventId,
        private string $typeId,
        private string $dateTime,
    ) {
    }

    public function eventId(): EventStepEventId
    {
        return new EventStepEventId($this->eventId);
    }

    public function typeId(): EventStepTypeId
    {
        return new EventStepTypeId($this->typeId);
    }

    /**
     * @throws Exception
     */
    public function dateTime(): EventStepDateTime
    {
        return new EventStepDateTime(new DateTimeImmutable($this->dateTime));
    }

    public static function fromScalars(string $eventId, string $typeId, string $dateTme): self
    {
        return new self($eventId, $typeId, $dateTme);
    }
}
