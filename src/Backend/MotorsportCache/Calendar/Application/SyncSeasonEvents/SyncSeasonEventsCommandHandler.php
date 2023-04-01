<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Event\PreviousSeasonEventsDeletedEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Event\SeasonEventsCreationFailedEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\DeleteSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SaveSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SeasonEventListGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\SeasonEvents;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Throwable;

final class SyncSeasonEventsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SeasonEventListGateway $eventListGateway,
        private readonly DeleteSeasonEventsGateway $deleteGateway,
        private readonly SaveSeasonEventsGateway $saveGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(SyncSeasonEventsCommand $command): ?UuidValueObject
    {
        $events = SeasonEventList::fromData(
            $this->eventListGateway->findEventsForSeason($command->championship(), $command->year())->data(),
        );

        $id = new UuidValueObject($this->uuidGenerator->uuid4());

        $slug = new StringValueObject(StringHelper::slugify($command->championship()->value()));

        $seasonEvents = SeasonEvents::create($id, $slug, $command->year(), $events);

        if ($this->deleteGateway->deleteIfExists($command->championship(), $command->year())) {
            $this->eventDispatcher->dispatch(PreviousSeasonEventsDeletedEvent::forSeason($command->championship(), $command->year()));
        }

        try {
            $this->saveGateway->save($seasonEvents);
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(SeasonEventsCreationFailedEvent::with($command->championship(), $command->year(), $e));

            return null;
        }

        $this->eventDispatcher->dispatch(...$seasonEvents->pullDomainEvents());

        return $id;
    }
}
