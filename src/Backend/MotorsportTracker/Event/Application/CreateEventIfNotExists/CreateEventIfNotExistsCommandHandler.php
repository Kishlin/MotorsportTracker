<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateEventIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchEventGateway $searchGateway,
        private readonly SaveEventGateway $saveGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateEventIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->find($command->slug()->value());
        if (null !== $id) {
            return $id;
        }

        $event = $this->createEventFromCommand($command);

        try {
            $this->saveGateway->save($event);
        } catch (Throwable $e) {
            throw new EventCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$event->pullDomainEvents());

        return $event->id();
    }

    private function createEventFromCommand(CreateEventIfNotExistsCommand $command): Event
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
