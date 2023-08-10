<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\ConstructorTeam;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Throwable;

final readonly class CreateConstructorTeamIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchConstructorTeamGateway $searchGateway,
        private SaveConstructorTeamGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateConstructorTeamIfNotExistsCommand $command): void
    {
        $existing = $this->searchGateway->has($command->constructor(), $command->team());

        if (true === $existing) {
            return;
        }

        $constructorTeam = ConstructorTeam::create($command->constructor(), $command->team());

        try {
            $this->saveGateway->save($constructorTeam);
        } catch (Throwable) {
            throw new ConstructorTeamCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$constructorTeam->pullDomainEvents());
    }
}
