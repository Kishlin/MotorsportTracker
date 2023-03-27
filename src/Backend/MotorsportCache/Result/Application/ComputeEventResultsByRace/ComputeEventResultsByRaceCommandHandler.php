<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsByRaceValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ComputeEventResultsByRaceCommandHandler implements CommandHandler
{
    public function __construct(
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
            new UuidValueObject($command->eventId()),
            ResultsByRaceValueObject::fromData($raceResultList),
        );

        $this->eventResultsByRaceGateway->save($eventResultsByRace);

        $this->eventDispatcher->dispatch(...$eventResultsByRace->pullDomainEvents());

        return $eventResultsByRace->id();
    }
}
