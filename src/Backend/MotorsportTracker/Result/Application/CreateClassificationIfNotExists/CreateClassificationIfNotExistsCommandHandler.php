<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateClassificationIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchClassificationGateway $searchGateway,
        private SaveClassificationGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateClassificationIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findForEntry($command->entry());
        if (null !== $id) {
            return $id;
        }

        $classification = Classification::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->entry(),
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
            $command->bestSpeed(),
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
