<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingConstructorGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingDriverGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingTeamGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Event\SeasonNotFoundEvent;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\ConstructorCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\ConstructorTeamCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\CountryCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\DriverCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\TeamCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SeasonDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsConstructorsIfNotExists\CreateAnalyticsConstructorsIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists\CreateAnalyticsDriversIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists\CreateAnalyticsTeamsIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\CreateOrUpdateStandingCommand;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsConstructorsStatsDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsDriversStatsDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsTeamsStatsDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final readonly class ScrapStandingsCommandHandler implements CommandHandler
{
    use ConstructorCreatorTrait;
    use ConstructorTeamCreatorTrait;
    use CountryCreatorTrait;
    use DriverCreatorTrait;
    use TeamCreatorTrait;

    public function __construct(
        private StandingConstructorGateway $standingConstructorGateway,
        private StandingDriverGateway $standingDriverGateway,
        private StandingTeamGateway $standingTeamGateway,
        private StandingsGateway $standingsGateway,
        private SeasonGateway $seasonGateway,
        private CommandBus $commandBus,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ScrapStandingsCommand $command): void
    {
        $season = $this->seasonGateway->find($command->championshipName(), $command->year());

        if (null === $season) {
            $this->eventDispatcher->dispatch(SeasonNotFoundEvent::forSeason($command->championshipName(), $command->year()));

            return;
        }

        $standings = $this->standingsGateway->fetchStandingsDataForSeason($season->ref())->standings();

        $this->scrapDriverStandings($season, $standings['driverStandings']);
        $this->scrapConstructorStandings($season, $standings['constructorStandings']);
        $this->scrapTeamStandings($season, $standings['teamStandings']);

        $this->eventDispatcher->dispatch(
            StandingsScrappedEvent::fromScalars($command->championshipName(), $command->year()),
        );
    }

    /**
     * @param array<int, null|array{name: string, uuid: string}> $driverStandings
     */
    private function scrapDriverStandings(SeasonDTO $season, array $driverStandings): void
    {
        if (empty($driverStandings)) {
            return;
        }

        foreach ($driverStandings as $driverStanding) {
            if (null === $driverStanding) {
                $seriesUuid = $seriesName = null;
            } else {
                $seriesName = $driverStanding['name'];
                $seriesUuid = $driverStanding['uuid'];
            }

            $standings = $this->standingDriverGateway->fetch($season->ref(), $seriesUuid)->standings()['standings'];

            foreach ($standings as $standing) {
                $countryId = null !== $standing['nationality']
                    ? $this->createCountryIfNotExists($standing['nationality'])->value()
                    : null;

                $driverId = $this->createDriverIfNotExists($standing['driver'], $countryId);

                $this->commandBus->execute(
                    CreateOrUpdateStandingCommand::fromScalars(
                        $season->id(),
                        $seriesName,
                        $driverId->value(),
                        $countryId,
                        $standing['position'],
                        $standing['points'],
                        StandingType::DRIVER,
                    ),
                );

                $this->commandBus->execute(
                    CreateAnalyticsDriversIfNotExistsCommand::fromScalars(
                        season: $season->id(),
                        driver: $driverId->value(),
                        country: $countryId,
                        position: $standing['position'],
                        points: $standing['points'],
                        analyticsStatsDTO: AnalyticsDriversStatsDTO::fromScalars(
                            avgFinishPosition: $standing['analytics']['avgFinishPosition'],
                            classWins: $standing['analytics']['classWins'],
                            fastestLaps: $standing['analytics']['fastestLaps'],
                            finalAppearances: $standing['analytics']['finalAppearances'],
                            hatTricks: $standing['analytics']['hatTricks'],
                            podiums: $standing['analytics']['podiums'],
                            poles: $standing['analytics']['poles'],
                            racesLed: $standing['analytics']['racesLed'],
                            ralliesLed: $standing['analytics']['ralliesLed'],
                            retirements: $standing['analytics']['retirements'],
                            semiFinalAppearances: $standing['analytics']['semiFinalAppearances'],
                            stageWins: $standing['analytics']['stageWins'],
                            starts: $standing['analytics']['starts'],
                            top10s: $standing['analytics']['top10s'],
                            top5s: $standing['analytics']['top5s'],
                            wins: $standing['analytics']['wins'],
                            winsPercentage: $standing['analytics']['winsPercentage'],
                        ),
                    ),
                );
            }
        }
    }

    /**
     * @param array<int, null|array{name: string, uuid: string}> $constructorStandings
     */
    private function scrapConstructorStandings(SeasonDTO $season, array $constructorStandings): void
    {
        if (empty($constructorStandings)) {
            return;
        }

        foreach ($constructorStandings as $constructorStanding) {
            if (null === $constructorStanding) {
                $seriesUuid = $seriesName = null;
            } else {
                $seriesName = $constructorStanding['name'];
                $seriesUuid = $constructorStanding['uuid'];
            }

            $standings = $this
                ->standingConstructorGateway
                ->fetch($season->ref(), $seriesUuid)
                ->standings()['standings']
            ;

            foreach ($standings as $standing) {
                $constructorId = $this->createConstructorIfNotExists(
                    $standing['constructor']['name'],
                    $standing['constructor']['uuid'],
                );

                $countryId = null;

                if (null !== $standing['team']) {
                    $countryId = null !== $standing['countryRepresenting']
                        ? $this->createCountryIfNotExists($standing['countryRepresenting'])->value()
                        : null;

                    $teamId = $this->createTeamIfNotExists(
                        $season->id(),
                        $standing['team']['name'],
                        $standing['team']['colour'],
                        $standing['team']['uuid'],
                    );

                    $this->createConstructorTeamIfNotExists($constructorId, $teamId);
                }

                $this->commandBus->execute(
                    CreateOrUpdateStandingCommand::fromScalars(
                        $season->id(),
                        $seriesName,
                        $constructorId->value(),
                        $countryId,
                        $standing['position'],
                        $standing['points'],
                        StandingType::CONSTRUCTOR,
                    ),
                );

                $this->commandBus->execute(
                    CreateAnalyticsConstructorsIfNotExistsCommand::fromScalars(
                        season: $season->id(),
                        constructor: $constructorId->value(),
                        country: $countryId,
                        position: $standing['position'],
                        points: $standing['points'],
                        analyticsStatsDTO: AnalyticsConstructorsStatsDTO::fromScalars(
                            wins: $standing['analytics']['wins'],
                        ),
                    ),
                );
            }
        }
    }

    /**
     * @param array<int, null|array{name: string, uuid: string}> $teamStandings
     */
    private function scrapTeamStandings(SeasonDTO $season, array $teamStandings): void
    {
        if (empty($teamStandings)) {
            return;
        }

        foreach ($teamStandings as $teamStanding) {
            if (null === $teamStanding) {
                $seriesUuid = $seriesName = null;
            } else {
                $seriesName = $teamStanding['name'];
                $seriesUuid = $teamStanding['uuid'];
            }

            $standings = $this->standingTeamGateway->fetch($season->ref(), $seriesUuid)->standings()['standings'];

            foreach ($standings as $standing) {
                $countryId = null !== $standing['countryRepresenting']
                    ? $this->createCountryIfNotExists($standing['countryRepresenting'])->value()
                    : null;

                $teamId = $this->createTeamIfNotExists(
                    $season->id(),
                    $standing['team']['name'],
                    $standing['team']['colour'],
                    $standing['team']['uuid'],
                );

                $this->commandBus->execute(
                    CreateOrUpdateStandingCommand::fromScalars(
                        $season->id(),
                        $seriesName,
                        $teamId->value(),
                        $countryId,
                        $standing['position'],
                        $standing['points'],
                        StandingType::TEAM,
                    ),
                );

                $this->commandBus->execute(
                    CreateAnalyticsTeamsIfNotExistsCommand::fromScalars(
                        season: $season->id(),
                        team: $teamId->value(),
                        country: $countryId,
                        position: $standing['position'],
                        points: $standing['points'],
                        analyticsStatsDTO: AnalyticsTeamsStatsDTO::fromScalars(
                            classWins: $standing['analytics']['classWins'],
                            fastestLaps: $standing['analytics']['fastestLaps'],
                            finalAppearances: $standing['analytics']['finalAppearances'],
                            finishesOneAndTwo: $standing['analytics']['finishes1And2'],
                            podiums: $standing['analytics']['podiums'],
                            poles: $standing['analytics']['poles'],
                            qualifiesOneAndTwo: $standing['analytics']['qualifies1And2'],
                            racesLed: $standing['analytics']['racesLed'],
                            ralliesLed: $standing['analytics']['ralliesLed'],
                            retirements: $standing['analytics']['retirements'],
                            semiFinalAppearances: $standing['analytics']['semiFinalAppearances'],
                            stageWins: $standing['analytics']['stageWins'],
                            starts: $standing['analytics']['starts'],
                            top10s: $standing['analytics']['top10s'],
                            top5s: $standing['analytics']['top5s'],
                            wins: $standing['analytics']['wins'],
                            winsPercentage: $standing['analytics']['winsPercentage'],
                        ),
                    ),
                );
            }
        }
    }
}
