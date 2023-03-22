<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateEntryIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly DriverByNameGateway $driverByNameGateway,
        private readonly SearchEntryGateway $searchEntryGateway,
        private readonly SaveEntryGateway $saveEntryGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateEntryIfNotExistsCommand $command): UuidValueObject
    {
        $driverId = $this->driverByNameGateway->find($command->driverName());
        if (null === $driverId) {
            throw new DriverNotFoundException();
        }

        $id = $this->searchEntryGateway->find($command->sessionId(), $driverId, $command->teamId(), $command->carNumber());
        if (null !== $id) {
            return $id;
        }

        $entry = Entry::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->sessionId(),
            $driverId,
            $command->teamId(),
            $command->carNumber(),
        );

        $this->saveEntryGateway->save($entry);

        $this->eventDispatcher->dispatch(...$entry->pullDomainEvents());

        return $entry->id();
    }
}
