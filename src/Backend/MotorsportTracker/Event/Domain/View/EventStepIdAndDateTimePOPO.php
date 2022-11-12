<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\View;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;

final class EventStepIdAndDateTimePOPO
{
    private function __construct(
        private string $eventStepId,
        private string $dateTime,
    ) {
    }

    public function eventStepId(): string
    {
        return $this->eventStepId;
    }

    public function dateTime(): string
    {
        return $this->dateTime;
    }

    /**
     * @param array{id: string, date_time: string} $data
     */
    public static function fromData(array $data): self
    {
        return new self($data['id'], $data['date_time']);
    }

    public static function fromEntity(EventStep $eventStep): self
    {
        return new self($eventStep->id()->value(), $eventStep->dateTime()->value()->format(DATE_ATOM));
    }
}
