<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics;

use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Event\SeasonNotFoundEvent;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\CountryCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\DriverCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists\CreateAnalyticsIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Throwable;

final class ScrapSeasonAnalyticsCommandHandler implements CommandHandler
{
    use CountryCreatorTrait;
    use DriverCreatorTrait;

    public function __construct(
        private readonly StandingsGateway $standingsGateway,
        private readonly SeasonGateway $seasonGateway,
        private readonly CommandBus $commandBus,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ScrapSeasonAnalyticsCommand $command): void
    {
        $season = $this->seasonGateway->find($command->championshipName(), $command->year());
        if (null === $season) {
            $this->eventDispatcher->dispatch(SeasonNotFoundEvent::forSeason($command->championshipName(), $command->year()));

            return;
        }

        foreach ($this->standingsGateway->fetch($season->ref())->data()['standings'] as $standing) {
            try {
                $countryId = $this->createCountryIfNotExists($standing['nationality']);

                $driverId = $this->createDriverIfNotExists($standing['driver'], $countryId);

                $this->commandBus->execute(
                    CreateAnalyticsIfNotExistsCommand::fromScalars(
                        season: $season->id(),
                        driver: $driverId->value(),
                        position: $standing['position'],
                        points: $standing['points'],
                        analyticsStatsDTO: AnalyticsStatsDTO::fromScalars(
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
            } catch (Throwable) {
                $this->eventDispatcher->dispatch(SeasonAnalyticsScrappingFailureEvent::forStanding($standing));
            }
        }
    }
}
