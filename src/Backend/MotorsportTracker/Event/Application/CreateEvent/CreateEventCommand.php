<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateEventCommand implements Command
{
    private function __construct(
        private string $seasonId,
        private string $venueId,
        private int $eventIndex,
        private string $eventLabel,
    ) {
    }

    public function seasonId(): EventSeasonId
    {
        return new EventSeasonId($this->seasonId);
    }

    public function venueId(): EventVenueId
    {
        return new EventVenueId($this->venueId);
    }

    public function eventIndex(): EventIndex
    {
        return new EventIndex($this->eventIndex);
    }

    public function eventLabel(): EventLabel
    {
        return new EventLabel($this->eventLabel);
    }

    public static function fromScalars(string $seasonId, string $venueId, int $eventIndex, string $eventLabel): self
    {
        return new self($seasonId, $venueId, $eventIndex, $eventLabel);
    }
}
