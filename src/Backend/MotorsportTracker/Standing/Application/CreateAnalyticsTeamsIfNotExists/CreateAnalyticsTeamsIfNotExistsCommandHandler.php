<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsTeams;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateAnalyticsTeamsIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchAnalyticsTeamsGateway $searchGateway,
        private SaveAnalyticsTeamsGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateAnalyticsTeamsIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->find($command->season(), $command->team());
        if (null !== $id) {
            return $id;
        }

        $analytics = AnalyticsTeams::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->season(),
            $command->team(),
            $command->country(),
            $command->position(),
            $command->points(),
            $command->analyticsStatsDTO(),
        );

        try {
            $this->saveGateway->save($analytics);
        } catch (Throwable) {
            throw new AnalyticsTeamsCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$analytics->pullDomainEvents());

        return $analytics->id();
    }
}
