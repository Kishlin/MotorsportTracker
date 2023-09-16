<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateOrUpdateEventSessionCommandHandler implements CommandHandler
{
    public function __construct(
        private UpdateEventSessionGateway $updateGateway,
        private SearchEventSessionGateway $searchGateway,
        private SaveEventSessionGateway $saveGateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CreateOrUpdateEventSessionCommand $command): UuidValueObject
    {
        $existing = $this->searchGateway->search($command->eventId(), $command->typeId(), $command->startDate());

        if (null === $existing) {
            return $this->createSessionFromCommand($command);
        }

        if ($this->commandDataEqualsExisting($command, $existing)) {
            return $existing->id();
        }

        $isOk = $this->updateGateway->update(
            $existing->id(),
            $command->endDate(),
            $command->hasResult(),
        );

        if ($isOk) {
            $this->eventDispatcher->dispatch(
                EventSessionUpdateSuccessEvent::forData(
                    $existing->id(),
                    $command->endDate(),
                    $command->hasResult(),
                ),
            );
        } else {
            $this->eventDispatcher->dispatch(
                EventSessionUpdateFailureEvent::forData(
                    $existing->id(),
                    $command->endDate(),
                    $command->hasResult(),
                ),
            );
        }

        return $existing->id();
    }

    /**
     * @throws Exception
     */
    private function createSessionFromCommand(CreateOrUpdateEventSessionCommand $command): UuidValueObject
    {
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
            throw new EventSessionCreationFailureException($e->getMessage(), previous: $e);
        }

        $this->eventDispatcher->dispatch(...$eventSession->pullDomainEvents());

        return $eventSession->id();
    }

    /**
     * @throws Exception
     */
    private function commandDataEqualsExisting(CreateOrUpdateEventSessionCommand $command, ExistingEventSessionDTO $DTO): bool
    {
        return $command->hasResult()->equals($DTO->hasResult()) && $command->endDate()->equals($DTO->endDate());
    }
}
