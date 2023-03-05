<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateEventCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveEventGateway $eventGateway,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateEventCommand $command): UuidValueObject
    {
        $event = $this->createEventFromCommand($command);

        try {
            $this->eventGateway->save($event);
        } catch (Throwable $e) {
            throw new EventCreationFailureException(previous: $e);
        }

        return $event->id();
    }

    private function createEventFromCommand(CreateEventCommand $command): Event
    {
        return Event::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->seasonId(),
            $command->venueId(),
            $command->index(),
            $command->slug(),
            $command->name(),
            $command->shortName(),
            $command->startTime(),
            $command->endTime(),
        );
    }
}
