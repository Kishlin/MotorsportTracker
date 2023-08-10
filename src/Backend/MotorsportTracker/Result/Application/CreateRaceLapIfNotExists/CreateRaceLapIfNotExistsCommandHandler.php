<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateRaceLapIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchRaceLapGateway $searchGateway,
        private SaveRaceLapGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateRaceLapIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findForEntryAndLap($command->entry(), $command->lap());
        if (null !== $id) {
            return $id;
        }

        $raceLap = RaceLap::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->entry(),
            $command->lap(),
            $command->position(),
            $command->pit(),
            $command->time(),
            $command->timeToLead(),
            $command->lapsToLead(),
            $command->timeToNext(),
            $command->lapsToNext(),
            $command->tyreDetails(),
        );

        try {
            $this->saveGateway->save($raceLap);
        } catch (Throwable) {
            throw new RaceLapCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$raceLap->pullDomainEvents());

        return $raceLap->id();
    }
}
