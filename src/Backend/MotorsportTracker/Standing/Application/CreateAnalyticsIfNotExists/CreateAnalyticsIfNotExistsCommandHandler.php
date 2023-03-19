<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Analytics;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateAnalyticsIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchAnalyticsGateway $searchGateway,
        private readonly SaveAnalyticsGateway $saveGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateAnalyticsIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->find($command->season(), $command->driver());
        if (null !== $id) {
            return $id;
        }

        $analytics = Analytics::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->season(),
            $command->driver(),
            $command->position(),
            $command->points(),
            $command->analyticsStatsDTO(),
        );

        try {
            $this->saveGateway->save($analytics);
        } catch (Throwable) {
            throw new AnalyticsCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$analytics->pullDomainEvents());

        return $analytics->id();
    }
}
