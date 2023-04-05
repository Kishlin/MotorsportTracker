<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Event\DidNotDuplicateEventCachedEvent;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Event\FoundNoEventToCacheEvent;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway\EventDataGateway;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway\SaveEventCacheGateway;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final class SyncEventCacheCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveEventCacheGateway $saveEventCacheGateway,
        private readonly EventDataGateway $eventDataGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(SyncEventCacheCommand $command): void
    {
        $eventData = $this->eventDataGateway->findAll();
        if (empty($eventData->data())) {
            $this->eventDispatcher->dispatch(FoundNoEventToCacheEvent::create());

            return;
        }

        foreach ($eventData->data() as $datum) {
            $this->cacheEvent($datum);
        }
    }

    /**
     * @param array{championship: string, year: int, event: string} $datum
     */
    private function cacheEvent(array $datum): void
    {
        $championship = StringHelper::slugify($datum['championship']);
        $event        = StringHelper::slugify($datum['event']);

        $eventCached = EventCached::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            new StringValueObject($championship),
            new StrictlyPositiveIntValueObject($datum['year']),
            new StringValueObject($event),
        );

        try {
            $this->saveEventCacheGateway->save($eventCached);
        } catch (EventCachedAlreadyExistException) {
            $this->eventDispatcher->dispatch(DidNotDuplicateEventCachedEvent::forEvent(
                $championship,
                $datum['year'],
                $event,
            ));

            return;
        }

        $this->eventDispatcher->dispatch(...$eventCached->pullDomainEvents());
    }
}
