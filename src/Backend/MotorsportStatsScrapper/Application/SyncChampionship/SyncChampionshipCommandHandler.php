<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\CreateSeasonIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\CreateEventIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\CreateEventSessionIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\CreateSessionTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\CreateVenueIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\DateTimeImmutableHelper;
use RuntimeException;

final class SyncChampionshipCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ChampionshipGateway $client,
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(SyncChampionshipCommand $command): void
    {
        $response = $this->client->fetch($command->seriesSlug(), $command->year());

        /** @var UuidValueObject $championshipId */
        $championshipId = $this->commandBus->execute(
            CreateChampionshipIfNotExistsCommand::fromScalars($response->series()->name(), $response->series()->slug()),
        );

        /** @var UuidValueObject $seasonId */
        $seasonId = $this->commandBus->execute(
            CreateSeasonIfNotExistsCommand::fromScalars($championshipId->value(), $command->year()),
        );

        foreach ($response->events() as $index => $eventDTO) {
            /** @var UuidValueObject $countryId */
            $countryId = $this->commandBus->execute(
                CreateCountryIfNotExistsCommand::fromScalars(
                    $this->countryCodeFromPicture($eventDTO->countryPicture()),
                    $eventDTO->countryName(),
                ),
            );

            /** @var UuidValueObject $venueId */
            $venueId = $this->commandBus->execute(
                CreateVenueIfNotExistsCommand::fromScalars(
                    $eventDTO->venueName(),
                    $eventDTO->venueSlug(),
                    $countryId->value(),
                ),
            );

            /** @var UuidValueObject $eventId */
            $eventId = $this->commandBus->execute(
                CreateEventIfNotExistsCommand::fromScalars(
                    $seasonId->value(),
                    $venueId->value(),
                    $index,
                    $eventDTO->slug(),
                    $eventDTO->name(),
                    $eventDTO->shortName(),
                    DateTimeImmutableHelper::fromNullableTimestamp($eventDTO->startTimeUtc()),
                    DateTimeImmutableHelper::fromNullableTimestamp($eventDTO->endTimeUtc()),
                ),
            );

            foreach ($eventDTO->sessions() as $sessionDTO) {
                /** @var UuidValueObject $sessionTypeId */
                $sessionTypeId = $this->commandBus->execute(
                    CreateSessionTypeIfNotExistsCommand::fromScalars(
                        $sessionDTO->name(),
                    ),
                );

                $this->commandBus->execute(
                    CreateEventSessionIfNotExistsCommand::fromScalars(
                        $eventId->value(),
                        $sessionTypeId->value(),
                        $sessionDTO->slug(),
                        $sessionDTO->hasResults(),
                        DateTimeImmutableHelper::fromNullableTimestamp($sessionDTO->startTimeUtc()),
                        DateTimeImmutableHelper::fromNullableTimestamp($sessionDTO->endTimeUtc()),
                    ),
                );
            }
        }
    }

    private function countryCodeFromPicture(string $countryPicture): string
    {
        if ('/' === $countryPicture[-6]) {
            throw new RuntimeException("Unexpected Country Picture format: {$countryPicture}");
        }

        return substr($countryPicture, -6, 2);
    }
}
