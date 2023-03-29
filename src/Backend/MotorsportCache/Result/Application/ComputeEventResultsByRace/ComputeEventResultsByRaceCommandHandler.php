<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Event\EventResultsByRaceCreationFailedEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Event\NoRacesToComputeEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Event\PreviousEventResultsByRaceDeletedEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\DeleteEventResultsByRaceIfExistsGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\EventResultsByRaceGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\RaceResultGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\RacesToComputeGateway;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsByRaceValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class ComputeEventResultsByRaceCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly DeleteEventResultsByRaceIfExistsGateway $deleteEventResultsByRaceGateway,
        private readonly EventResultsByRaceGateway $eventResultsByRaceGateway,
        private readonly RacesToComputeGateway $racesToComputeGateway,
        private readonly RaceResultGateway $raceResultGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(ComputeEventResultsByRaceCommand $command): ?UuidValueObject
    {
        $racesToCompute = $this->racesToComputeGateway->findRaces($command->eventId())->races();

        if (empty($racesToCompute)) {
            $this->eventDispatcher->dispatch(NoRacesToComputeEvent::forEvent($command->eventId()));

            return null;
        }

        try {
            return $this->createEventResultsByRaceForEvent($racesToCompute, $command->eventId());
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(EventResultsByRaceCreationFailedEvent::withScalars($command->eventId(), $e));

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
            $this->eventDispatcher->dispatch(PreviousEventResultsByRaceDeletedEvent::forEvent($eventId));
        }

        $this->eventResultsByRaceGateway->save($eventResultsByRace);

        $this->eventDispatcher->dispatch(...$eventResultsByRace->pullDomainEvents());

        return $eventResultsByRace->id();
    }
}
