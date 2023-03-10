<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateEntryIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveEntryGateway $saveGateway,
        private readonly SearchEntryGateway $searchGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateEntryIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->find($command->event(), $command->driver(), $command->carNumber());
        if (null !== $id) {
            return $id;
        }

        $id    = new UuidValueObject($this->uuidGenerator->uuid4());
        $entry = Entry::create(
            $id,
            $command->event(),
            $command->driver(),
            $command->team(),
            $command->chassis(),
            $command->engine(),
            $command->seriesName(),
            $command->seriesSlug(),
            $command->carNumber(),
        );

        try {
            $this->saveGateway->save($entry);
        } catch (Throwable) {
            throw new EntryCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$entry->pullDomainEvents());

        return $id;
    }
}
