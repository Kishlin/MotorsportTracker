<?php

/**
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\AvailableStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\ConstructorStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\TeamStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\ConstructorStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\TeamStandingsView;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final readonly class UpdateSeasonStandingsCacheCommandHandler implements CommandHandler
{
    public function __construct(
        private StandingsDataGateway $standingsDataGateway,
        private CachePersister $cachePersister,
    ) {}

    /**
     * @throws JsonException
     */
    public function __invoke(UpdateSeasonStandingsCacheCommand $command): void
    {
        $standingsData = $this->standingsDataGateway->findForSeason($command->championshipName(), $command->year());

        $championshipSlug = StringHelper::slugify($command->championshipName());
        $keyData          = ['championship' => $championshipSlug, 'year' => $command->year()];

        $hasConstructor = $hasTeam = $hasDriver = false;

        if (false === empty($standingsData->constructorStandings())) {
            $constructorStandings = ConstructorStandings::create(
                ConstructorStandingsView::with($standingsData->constructorStandings()),
            );

            $this->cachePersister->save($constructorStandings, $keyData);

            $hasConstructor = true;
        }

        if (false === empty($standingsData->teamStandings())) {
            $teamStandings = TeamStandings::create(
                TeamStandingsView::with($standingsData->teamStandings()),
            );

            $this->cachePersister->save($teamStandings, $keyData);

            $hasTeam = true;
        }

        if (false === empty($standingsData->driverStandings())) {
            $driverStandings = DriverStandings::create(
                DriverStandingsView::with($standingsData->driverStandings()),
            );

            $this->cachePersister->save($driverStandings, $keyData);

            $hasDriver = true;
        }

        $availableStandings = AvailableStandings::create($hasConstructor, $hasTeam, $hasDriver);

        $this->cachePersister->save($availableStandings, $keyData);
    }
}
