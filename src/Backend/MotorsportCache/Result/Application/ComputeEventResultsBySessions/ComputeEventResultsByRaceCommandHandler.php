<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Event\EventResultsBySessionsCreationFailedEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Event\NoSessionsToComputeEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Event\PreviousEventResultsBySessionsDeletedEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\DeleteEventResultsBySessionsIfExistsGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\EventResultsBySessionsGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\SessionClassificationGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\SessionsToComputeGateway;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsByRaceValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class ComputeEventResultsByRaceCommandHandler implements CommandHandler
{
    public function __construct(
        private DeleteEventResultsBySessionsIfExistsGateway $deleteEventResultsByRaceGateway,
        private EventResultsBySessionsGateway $eventResultsByRaceGateway,
        private SessionsToComputeGateway $sessionsToComputeGateway,
        private SessionClassificationGateway $raceResultGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(ComputeEventResultsByRaceCommand $command): ?UuidValueObject
    {
        $sessionsToCompute = $this->sessionsToComputeGateway->findSessions($command->eventId())->sessions();

        if (empty($sessionsToCompute)) {
            $this->eventDispatcher->dispatch(NoSessionsToComputeEvent::forEvent($command->eventId()));

            return null;
        }

        try {
            return $this->createEventResultsByRaceForEvent($sessionsToCompute, $command->eventId());
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(EventResultsBySessionsCreationFailedEvent::withScalars($command->eventId(), $e));

            return null;
        }
    }

    /**
     * @param array<array{id: string, type: string}> $racesToCompute
     */
    private function createEventResultsByRaceForEvent(array $racesToCompute, string $eventId): UuidValueObject
    {
        $raceResultList = [];
        foreach ($racesToCompute as $raceToCompute) {
            $raceResult = $this->raceResultGateway->findResult($raceToCompute['id']);

            $raceResultList[] = [
                'session' => [
                    'id'   => $raceToCompute['id'],
                    'type' => $raceToCompute['type'],
                ],
                'result' => $raceResult->results(),
            ];
        }

        $eventResultsByRace = EventResultsByRace::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            new UuidValueObject($eventId),
            ResultsByRaceValueObject::fromData($raceResultList),
        );

        $itDeletedSomething = $this->deleteEventResultsByRaceGateway->deleteIfExists($eventId);
        if ($itDeletedSomething) {
            $this->eventDispatcher->dispatch(PreviousEventResultsBySessionsDeletedEvent::forEvent($eventId));
        }

        $this->eventResultsByRaceGateway->save($eventResultsByRace);

        $this->eventDispatcher->dispatch(...$eventResultsByRace->pullDomainEvents());

        return $eventResultsByRace->id();
    }
}
