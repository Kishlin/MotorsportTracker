<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateEventSessionIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchEventSessionGateway $searchGateway,
        private readonly SaveEventSessionGateway $saveGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CreateEventSessionIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->search($command->eventId(), $command->startDate());
        if (null !== $id) {
            return $id;
        }

        $eventSession = EventSession::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->eventId(),
            $command->typeId(),
            $command->hasResult(),
            $command->startDate(),
            $command->endDate(),
            $command->ref(),
        );

        try {
            $this->saveGateway->save($eventSession);
        } catch (Throwable $e) {
            throw new EventSessionCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$eventSession->pullDomainEvents());

        return $eventSession->id();
    }
}
