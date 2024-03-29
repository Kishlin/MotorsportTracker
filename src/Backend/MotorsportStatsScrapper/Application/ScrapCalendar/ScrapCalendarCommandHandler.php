<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Event\SeasonNotFoundEvent;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\CountryCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\CreateEventIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\CreateOrUpdateEventSessionCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\CreateSessionTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\CreateVenueIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\DateTimeImmutableHelper;
use Throwable;

final class ScrapCalendarCommandHandler implements CommandHandler
{
    use CountryCreatorTrait;

    public function __construct(
        private readonly CalendarGateway $calendarGateway,
        private readonly SeasonGateway $seasonGateway,
        private readonly CommandBus $commandBus,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ScrapCalendarCommand $command): void
    {
        $season = $this->seasonGateway->find($command->championshipName(), $command->year());
        if (null === $season) {
            $this->eventDispatcher->dispatch(SeasonNotFoundEvent::forSeason($command->championshipName(), $command->year()));

            return;
        }

        foreach ($this->calendarGateway->fetch($season->ref())->data()['events'] as $key => $event) {
            try {
                $countryId = $this->createCountryIfNotExists($event['country']);

                $venueId = $this->createVenueIfNotExists($event['venue'], $countryId);

                $eventId = $this->createEventIfNotExists($event, $season->id(), $venueId, $key);

                $this->createEventSessionsIfNotExists($event['sessions'], $eventId);
            } catch (Throwable $e) {
                $this->eventDispatcher->dispatch(CalendarEventScrappingFailureEvent::forEvent($event, $e));
            }
        }

        $this->eventDispatcher->dispatch(
            CalendarEventScrappingSuccessEvent::forSeason($command->championshipName(), $command->year()),
        );
    }

    /**
     * @param array{name: string, uuid: string, shortName: string, shortCode: string, picture: string} $venue
     */
    private function createVenueIfNotExists(array $venue, UuidValueObject $countryId): UuidValueObject
    {
        $command = CreateVenueIfNotExistsCommand::fromScalars($venue['name'], $countryId->value(), $venue['uuid']);
        $venueId = $this->commandBus->execute($command);

        assert($venueId instanceof UuidValueObject);

        return $venueId;
    }

    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startDate: ?int,
     *     startTimeUtc: ?int,
     *     endDate: ?int,
     *     endTimeUtc: ?int,
     * } $event
     */
    private function createEventIfNotExists(array $event, string $seasonId, UuidValueObject $venueId, int $key): UuidValueObject
    {
        $eventId = $this->commandBus->execute(
            CreateEventIfNotExistsCommand::fromScalars(
                seasonId: $seasonId,
                venueId: $venueId->value(),
                index: $key,
                name: $event['name'],
                shortName: $event['shortName'],
                shortCode: $event['shortCode'],
                status: empty($event['status']) ? null : $event['status'],
                startTime: $this->dateTimeOrNull($event['startTimeUtc']),
                endTime: $this->dateTimeOrNull($event['endTimeUtc']),
                ref: $event['uuid'],
            ),
        );

        assert($eventId instanceof UuidValueObject);

        return $eventId;
    }

    /**
     * @param array<array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     hasResults: bool,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     * }> $sessions
     */
    private function createEventSessionsIfNotExists(array $sessions, UuidValueObject $eventId): void
    {
        foreach ($sessions as $session) {
            $sessionTypeId = $this->commandBus->execute(
                CreateSessionTypeIfNotExistsCommand::fromScalars($session['name']),
            );

            assert($sessionTypeId instanceof UuidValueObject);

            $this->commandBus->execute(
                CreateOrUpdateEventSessionCommand::fromScalars(
                    eventId: $eventId->value(),
                    typeId: $sessionTypeId->value(),
                    hasResult: $session['hasResults'],
                    startDate: $this->dateTimeOrNull($session['startTimeUtc']),
                    endDate: $this->dateTimeOrNull($session['endTimeUtc']),
                    ref: $session['uuid'],
                ),
            );
        }
    }

    private function dateTimeOrNull(?int $timestamp): ?DateTimeImmutable
    {
        if (null === $timestamp) {
            return null;
        }

        return DateTimeImmutableHelper::fromTimestamp($timestamp);
    }
}
