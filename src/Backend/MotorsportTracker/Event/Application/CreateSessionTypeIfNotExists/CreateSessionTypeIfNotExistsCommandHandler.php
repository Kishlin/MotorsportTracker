<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateSessionTypeIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SessionTypeIdForLabelGateway $sessionTypeIdForLabelGateway,
        private readonly SaveSessionTypeGateway $sessionTypeGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateSessionTypeIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->sessionTypeIdForLabelGateway->idForLabel($command->label());

        if (null !== $id) {
            return $id;
        }

        $newId       = new UuidValueObject($this->uuidGenerator->uuid4());
        $sessionType = SessionType::create($newId, $command->label());

        $this->sessionTypeGateway->save($sessionType);

        $this->eventDispatcher->dispatch(...$sessionType->pullDomainEvents());

        return $sessionType->id();
    }
}
