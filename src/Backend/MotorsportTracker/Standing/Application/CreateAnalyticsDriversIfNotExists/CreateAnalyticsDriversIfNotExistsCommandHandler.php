<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateAnalyticsDriversIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchAnalyticsDriversGateway $searchGateway,
        private SaveAnalyticsDriversGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateAnalyticsDriversIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->find($command->season(), $command->driver());
        if (null !== $id) {
            return $id;
        }

        $analytics = AnalyticsDrivers::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->season(),
            $command->driver(),
            $command->country(),
            $command->position(),
            $command->points(),
            $command->analyticsStatsDTO(),
        );

        try {
            $this->saveGateway->save($analytics);
        } catch (Throwable) {
            throw new AnalyticsDriversCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$analytics->pullDomainEvents());

        return $analytics->id();
    }
}
