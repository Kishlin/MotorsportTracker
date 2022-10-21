<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\EventStepGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateEventStepCommandHandler implements CommandHandler
{
    public function __construct(
        private EventHasStepAtTheSameTimeGateway $eventHasStepAtTheSameTimeGateway,
        private EventHasStepWithTypeGateway $eventHasStepWithTypeGateway,
        private EventStepGateway $eventStepGateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CreateEventStepCommand $command): EventStepId
    {
        $this->refuseCreationIfEventAlreadyHasStepAtTheSameTime($command);
        $this->refuseCreationIfEventAlreadyHasStepOfThisType($command);

        $eventStep = $this->createEventFromCommand($command);

        try {
            $this->eventStepGateway->save($eventStep);
        } catch (Throwable $e) {
            throw new EventStepCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$eventStep->pullDomainEvents());

        return $eventStep->id();
    }

    /**
     * @throws Exception
     */
    private function refuseCreationIfEventAlreadyHasStepAtTheSameTime(CreateEventStepCommand $command): void
    {
        if ($this->eventHasStepAtTheSameTimeGateway->eventHasStepAtTheSameTime(
            $command->eventId(),
            $command->dateTime(),
        )) {
            throw new EventHasStepAtTheSameTimeException();
        }
    }

    private function refuseCreationIfEventAlreadyHasStepOfThisType(CreateEventStepCommand $command): void
    {
        if ($this->eventHasStepWithTypeGateway->eventHasStepWithType($command->eventId(), $command->typeId())) {
            throw new EventHasStepWithTypeException();
        }
    }

    private function createEventFromCommand(CreateEventStepCommand $command): EventStep
    {
        $eventStepId = new EventStepId($this->uuidGenerator->uuid4());

        return EventStep::create($eventStepId, $command->typeId(), $command->eventId(), $command->dateTime());
    }
}
