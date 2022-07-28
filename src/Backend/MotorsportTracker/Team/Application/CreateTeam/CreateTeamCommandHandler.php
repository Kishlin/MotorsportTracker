<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Gateway\TeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateTeamCommandHandler implements CommandHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
        private TeamGateway $gateway,
    ) {
    }

    public function __invoke(CreateTeamCommand $command): TeamId
    {
        $id = new TeamId($this->uuidGenerator->uuid4());

        $team = Team::create($id, $command->name(), $command->image(), $command->countryId());

        try {
            $this->gateway->save($team);
        } catch (Throwable $e) {
            throw new TeamCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$team->pullDomainEvents());

        return $team->id();
    }
}
