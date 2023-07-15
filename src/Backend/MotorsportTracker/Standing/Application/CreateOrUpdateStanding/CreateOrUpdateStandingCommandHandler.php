<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Standing;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateOrUpdateStandingCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchStandingGateway $searchGateway,
        private UpdateStandingGateway $updateGateway,
        private SaveStandingGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateOrUpdateStandingCommand $command): ?UuidValueObject
    {
        $existingId = $this->searchGateway->findForSeasonClassAndDriver(
            $command->season(),
            $command->seriesClass(),
            $command->standee(),
            $command->standingType(),
        );

        if (null !== $existingId) {
            return $this->updateExistingStanding($existingId, $command);
        }

        return $this->createNewStanding($command);
    }

    private function updateExistingStanding(UuidValueObject $existingId, CreateOrUpdateStandingCommand $command): ?UuidValueObject
    {
        $isOk = $this->updateGateway->update($existingId, $command->standingType(), $command->position(), $command->points());

        if (false === $isOk) {
            $this->raiseStandingEvent(StandingUpdateFailureEvent::class, $command);

            return null;
        }

        $this->raiseStandingEvent(StandingUpdatedEvent::class, $command);

        return $existingId;
    }

    private function createNewStanding(CreateOrUpdateStandingCommand $command): ?UuidValueObject
    {
        $standing = Standing::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->season(),
            $command->standee(),
            $command->seriesClass(),
            $command->position(),
            $command->points(),
            $command->standingType(),
        );

        try {
            $this->saveGateway->save($standing);
        } catch (Throwable) {
            $this->raiseStandingEvent(StandingCreationFailureEvent::class, $command);

            return null;
        }

        $this->eventDispatcher->dispatch(...$standing->pullDomainEvents());

        return $standing->id();
    }

    /**
     * @param class-string<AbstractStandingEvent> $event
     */
    private function raiseStandingEvent(string $event, CreateOrUpdateStandingCommand $command): void
    {
        $this->eventDispatcher->dispatch(
            $event::forSeasonClassAndStandee(
                $command->season(),
                $command->seriesClass(),
                $command->standee(),
                $command->standingType(),
            ),
        );
    }
}
