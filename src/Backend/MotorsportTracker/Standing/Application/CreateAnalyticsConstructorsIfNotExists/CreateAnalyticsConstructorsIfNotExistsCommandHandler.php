<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsConstructorsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsConstructors;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateAnalyticsConstructorsIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchAnalyticsConstructorsGateway $searchGateway,
        private SaveAnalyticsConstructorsGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateAnalyticsConstructorsIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->find($command->season(), $command->constructor());
        if (null !== $id) {
            return $id;
        }

        $analytics = AnalyticsConstructors::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->season(),
            $command->constructor(),
            $command->country(),
            $command->position(),
            $command->points(),
            $command->analyticsStatsDTO(),
        );

        try {
            $this->saveGateway->save($analytics);
        } catch (Throwable) {
            throw new AnalyticsConstructorsCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$analytics->pullDomainEvents());

        return $analytics->id();
    }
}
