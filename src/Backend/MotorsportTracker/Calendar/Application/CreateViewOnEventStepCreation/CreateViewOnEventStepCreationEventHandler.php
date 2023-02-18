<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewColor;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewId;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewName;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewReferenceId;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewType;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewVenueLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventStepCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;

final class CreateViewOnEventStepCreationEventHandler implements DomainEventSubscriber
{
    public function __construct(
        private readonly CalendarEventStepViewGateway $calendarEventStepViewGateway,
        private readonly EventStepViewDataGateway $eventStepViewDataGateway,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(EventStepCreatedDomainEvent $event): void
    {
        $data = $this->eventStepViewDataGateway->find($event->aggregateUuid());

        if (null === $data) {
            throw new EventStepViewDataNotFoundException();
        }

        $calendarView = CalendarEventStepView::create(
            new CalendarEventStepViewId($this->uuidGenerator->uuid4()),
            new CalendarEventStepViewChampionshipSlug($data->championshipSlug()),
            new CalendarEventStepViewColor($data->color()),
            new CalendarEventStepViewIcon($data->icon()),
            new CalendarEventStepViewName($data->name()),
            new CalendarEventStepViewVenueLabel($data->venueLabel()),
            new CalendarEventStepViewType($data->type()),
            new CalendarEventStepViewDateTime($data->dateTime()),
            CalendarEventStepViewReferenceId::fromOther($event->aggregateUuid()),
        );

        $this->calendarEventStepViewGateway->save($calendarView);
    }

    public static function subscribedTo(): array
    {
        return [
            EventStepCreatedDomainEvent::class,
        ];
    }
}
