<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindEventsGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventSessionRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveSessionTypeRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\SaveVenueRepositorySpy;

final class FindEventsRepositorySpy implements FindEventsGateway
{
    /** @var array<string, string> */
    private array $typeCache = [];

    public function __construct(
        private readonly SaveSessionTypeRepositorySpy $sessionTypeRepositorySpy,
        private readonly EventSessionRepositorySpy $eventSessionRepositorySpy,
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
        private readonly SaveEventRepositorySpy $saveEventRepositorySpy,
        private readonly SaveSeasonRepositorySpy $seasonRepositorySpy,
        private readonly SaveVenueRepositorySpy $venueRepositorySpy,
    ) {
    }

    public function findAll(StringValueObject $seriesSlug, PositiveIntValueObject $year): array
    {
        $championshipId = $this->championshipRepositorySpy->findIfExists($seriesSlug, new NullableUuidValueObject(null));
        if (null === $championshipId) {
            return [];
        }

        $seasonId = $this->seasonRepositorySpy->find($championshipId->value(), $year->value());
        if (null === $seasonId) {
            return [];
        }

        $eventsList = [];
        foreach ($this->saveEventRepositorySpy->all() as $event) {
            if (false === $event->seasonId()->equals($seasonId)) {
                continue;
            }

            $venue   = $this->venueRepositorySpy->safeGet($event->venueId());
            $country = $this->countryRepositorySpy->safeGet($venue->countryId());

            $eventsList[$event->id()->value()] = [
                'venue' => [
                    'name'    => $venue->name()->value(),
                    'slug'    => $venue->name()->value(),
                    'country' => [
                        'code' => $country->code()->value(),
                        'name' => $country->name()->value(),
                    ],
                ],
                'index'      => $event->index()->value(),
                'slug'       => $event->slug()->value(),
                'name'       => $event->name()->value(),
                'short_name' => $event->shortName()->value(),
                'start_date' => $event->startDate()->value()?->format('Y-m-d H:i:s'),
                'end_date'   => $event->endDate()->value()?->format('Y-m-d H:i:s'),
                'sessions'   => [],
            ];
        }

        foreach ($this->eventSessionRepositorySpy->all() as $eventSession) {
            $eventId = $eventSession->eventId()->value();
            if (false === array_key_exists($eventId, $eventsList)) {
                continue;
            }

            $eventsList[$eventId]['sessions'][] = [
                'type'       => $this->memoizedType($eventSession->typeId()),
                'slug'       => $eventSession->slug()->value(),
                'has_result' => $eventSession->hasResult()->value(),
                'start_date' => $eventSession->startDate()->value()?->format('Y-m-d H:i:s'),
                'end_date'   => $eventSession->endDate()->value()?->format('Y-m-d H:i:s'),
            ];
        }

        return array_map(
            static function ($data) { return CalendarEventEntry::fromData($data); },
            $eventsList,
        );
    }

    private function memoizedType(UuidValueObject $typeId): string
    {
        if (false === array_key_exists($typeId->value(), $this->typeCache)) {
            $this->typeCache[$typeId->value()] = $this->sessionTypeRepositorySpy->safeGet($typeId)->label()->value();
        }

        return $this->typeCache[$typeId->value()];
    }
}
