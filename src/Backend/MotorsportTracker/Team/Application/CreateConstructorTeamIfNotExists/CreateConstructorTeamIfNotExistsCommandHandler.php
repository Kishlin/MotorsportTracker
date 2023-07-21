<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\ConstructorTeam;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateConstructorTeamIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchConstructorTeamGateway $searchGateway,
        private SaveConstructorTeamGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateConstructorTeamIfNotExistsCommand $command): UuidValueObject
    {
        $existing = $this->searchGateway->find($command->constructor(), $command->team());

        if (null !== $existing) {
            return $existing;
        }

        $constructorTeam = ConstructorTeam::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->constructor(),
            $command->team(),
        );

        try {
            $this->saveGateway->save($constructorTeam);
        } catch (Throwable) {
            throw new ConstructorTeamCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$constructorTeam->pullDomainEvents());

        return $constructorTeam->id();
    }
}
