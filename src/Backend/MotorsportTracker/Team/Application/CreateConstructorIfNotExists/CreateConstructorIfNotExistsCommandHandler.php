<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Constructor;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateConstructorIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchConstructorGateway $searchGateway,
        private SaveConstructorGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateConstructorIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findForNameAndRef($command->name(), $command->ref());

        if (null !== $id) {
            return $id;
        }

        $id = new UuidValueObject($this->uuidGenerator->uuid4());

        $constructor = Constructor::create($id, $command->name(), $command->ref());

        try {
            $this->saveGateway->save($constructor);
        } catch (Throwable $e) {
            throw new ConstructorCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$constructor->pullDomainEvents());

        return $constructor->id();
    }
}
