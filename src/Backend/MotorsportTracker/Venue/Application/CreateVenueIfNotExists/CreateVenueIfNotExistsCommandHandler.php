<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateVenueIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchVenueGateway $searchGateway,
        private readonly SaveVenueGateway $saveGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateVenueIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->search($command->slug()->value());

        if (null !== $id) {
            return $id;
        }

        $newId = new UuidValueObject($this->uuidGenerator->uuid4());
        $venue = Venue::create($newId, $command->name(), $command->slug(), $command->countryId());

        $this->saveGateway->save($venue);

        $this->eventDispatcher->dispatch(...$venue->pullDomainEvents());

        return $venue->id();
    }
}
