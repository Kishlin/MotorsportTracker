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

final readonly class SyncCalendarEventsCommandHandler implements CommandHandler
{
    public function __construct(
        private SaveCalendarEventGateway $saveGateway,
        private FindSeriesGateway $seriesGateway,
        private FindEventsGateway $eventsGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(SyncCalendarEventsCommand $command): void
    {
        $series = $this->seriesGateway->findForChampionship($command->championship(), $command->year());

        if (null === $series) {
            $this->eventDispatcher->dispatch(SeriesIsMissingApplicationEvent::forSlug($command->championship()->value()));

            return;
        }

        foreach ($this->eventsGateway->findAll($command->championship(), $command->year()) as $eventEntry) {
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
