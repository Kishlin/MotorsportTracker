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
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\CreateOrUpdateStandingCommand;
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
                $countryId = $this->createCountryIfNotExists($standing['nationality']);
                $driverId  = $this->createDriverIfNotExists($standing['driver']);

                $this->commandBus->execute(
                    CreateOrUpdateStandingCommand::fromScalars(
                        $season->id(),
                        $seriesName,
                        $driverId->value(),
                        $countryId->value(),
                        $standing['position'],
                        $standing['totalPoints'],
                        StandingType::DRIVER,
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
            }
        }
    }
}
