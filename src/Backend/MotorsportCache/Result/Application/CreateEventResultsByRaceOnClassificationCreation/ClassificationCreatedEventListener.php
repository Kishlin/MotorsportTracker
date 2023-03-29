<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\CreateEventResultsByRaceOnClassificationCreation;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\ComputeEventResultsByRaceCommand;
use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ClassificationCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;

final class ClassificationCreatedEventListener implements EventSubscriber
{
    public function __construct(
        private readonly EventDispatcher $eventDispatcher,
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(ClassificationCreatedDomainEvent $event): void
    {
        $eventId = $event->aggregateUuid()->value();

        $this->eventDispatcher->dispatch(EventResultsByRaceCreationRequestedEvent::forEvent($eventId));

        $this->commandBus->execute(ComputeEventResultsByRaceCommand::fromScalars($eventId));
    }
}
