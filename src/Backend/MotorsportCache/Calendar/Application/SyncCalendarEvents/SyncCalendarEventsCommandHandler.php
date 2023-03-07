<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event\CalendarEventCreatedApplicationEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event\CalendarEventUpdatedApplicationEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event\SeriesIsMissingApplicationEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindSeriesGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\SaveCalendarEventGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SyncCalendarEventsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveCalendarEventGateway $saveGateway,
        private readonly FindSeriesGateway $seriesGateway,
        private readonly FindEventsGateway $eventsGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(SyncCalendarEventsCommand $command): void
    {
        $series = $this->seriesGateway->findForSlug($command->seasonSlug(), $command->year());

        if (null === $series) {
            $this->eventDispatcher->dispatch(SeriesIsMissingApplicationEvent::forSlug($command->seasonSlug()->value()));

            return;
        }

        foreach ($this->eventsGateway->findAll($command->seasonSlug(), $command->year()) as $eventEntry) {
            $id    = new UuidValueObject($this->uuidGenerator->uuid4());
            $event = CalendarEvent::withEntry($id, $series, $eventEntry);

            $action = $this->saveGateway->save($event);

            if (CalendarEventUpsert::CREATED === $action) {
                $this->eventDispatcher->dispatch(CalendarEventCreatedApplicationEvent::forCalendarEvent($event));
            } else {
                $this->eventDispatcher->dispatch(CalendarEventUpdatedApplicationEvent::forCalendarEvent($event));
            }
        }
    }
}
