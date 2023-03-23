<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Exception\EntryNotFoundException;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Gateway\FindEntryForSessionAndNumberGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateClassificationIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly FindEntryForSessionAndNumberGateway $findEntryGateway,
        private readonly SearchClassificationGateway $searchGateway,
        private readonly SaveClassificationGateway $saveGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateClassificationIfNotExistsCommand $command): UuidValueObject
    {
        $entry = $this->findEntryGateway->findForSessionAndNumber($command->session(), $command->number());
        if (null === $entry) {
            throw new EntryNotFoundException();
        }

        $id = $this->searchGateway->findForEntry($entry);
        if (null !== $id) {
            return $id;
        }

        $classification = Classification::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $entry,
            $command->finishPosition(),
            $command->gridPosition(),
            $command->laps(),
            $command->points(),
            $command->time(),
            $command->classifiedStatus(),
            $command->averageLapSpeed(),
            $command->fastestLapTime(),
            $command->gapTimeToLead(),
            $command->gapTimeToNext(),
            $command->gapLapsToLead(),
            $command->gapLapsToNext(),
            $command->bestLap(),
            $command->bestTime(),
            $command->bestIsFastest(),
        );

        try {
            $this->saveGateway->save($classification);
        } catch (Throwable) {
            throw new ClassificationCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$classification->pullDomainEvents());

        return $classification->id();
    }
}
