<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\EventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateEventCommandHandler implements CommandHandler
{
    public function __construct(
        private EventCreationCheckGateway $eventCreationCheckGateway,
        private EventGateway $eventGateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateEventCommand $command): EventId
    {
        $this->refuseCreationIfSpotIsAlreadyTaken($command);

        $event = $this->createEventFromCommand($command);

        try {
            $this->eventGateway->save($event);
        } catch (Throwable $e) {
            throw new EventCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$event->pullDomainEvents());

        return $event->id();
    }

    private function refuseCreationIfSpotIsAlreadyTaken(CreateEventCommand $command): void
    {
        if ($this->eventCreationCheckGateway->seasonHasEventWithIndexOrVenue(
            $command->seasonId(),
            $command->eventIndex(),
            $command->eventLabel(),
        )) {
            throw new SeasonHasEventWithIndexOrVenueException();
        }
    }

    private function createEventFromCommand(CreateEventCommand $command): Event
    {
        return Event::create(
            new EventId($this->uuidGenerator->uuid4()),
            $command->seasonId(),
            $command->venueId(),
            $command->eventIndex(),
            $command->eventLabel(),
        );
    }
}
