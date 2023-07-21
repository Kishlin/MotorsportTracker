<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateTeamIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchTeamGateway $searchGateway,
        private SaveTeamGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateTeamIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findForSeasonNameAndRef($command->seasonId(), $command->name(), $command->ref());

        if (null !== $id) {
            return $id;
        }

        $id   = new UuidValueObject($this->uuidGenerator->uuid4());
        $team = Team::create(
            $id,
            $command->seasonId(),
            $command->countryId(),
            $command->name(),
            $command->color(),
            $command->ref(),
        );

        try {
            $this->saveGateway->save($team);
        } catch (Throwable $e) {
            throw new TeamCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$team->pullDomainEvents());

        return $team->id();
    }
}
