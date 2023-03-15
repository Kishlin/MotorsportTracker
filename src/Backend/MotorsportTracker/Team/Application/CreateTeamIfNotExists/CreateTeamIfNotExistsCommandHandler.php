<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateTeamIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchTeamGateway $searchGateway,
        private readonly SaveTeamGateway $saveGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateTeamIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findForRef($command->ref());

        if (null !== $id) {
            return $id;
        }

        $id   = new UuidValueObject($this->uuidGenerator->uuid4());
        $team = Team::create($id, $command->ref());

        try {
            $this->saveGateway->save($team);
        } catch (Throwable $e) {
            throw new TeamCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$team->pullDomainEvents());

        return $team->id();
    }
}
