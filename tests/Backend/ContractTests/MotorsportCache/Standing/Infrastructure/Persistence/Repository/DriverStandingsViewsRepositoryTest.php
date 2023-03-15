<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Standing\Infrastructure\Persistence\Repository;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\DriverStandingsViewsRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\DriverStandingsViewsRepository
 */
final class DriverStandingsViewsRepositoryTest extends CacheRepositoryContractTestCase
{
    /**
     * @throws JsonException
     */
    public function testItCanSaveAndRetrieveAnEntity(): void
    {
        $events    = StandingsViewEvents::with('fr', 'gb', 'es', 'en');
        $standings = StandingsViewStandings::with(
            ['name' => 'Driver 1', 'color' => 'white', 'totals' => [0, 10, 15, 25]],
            ['name' => 'Driver 2', 'color' => 'black', 'totals' => [7, 15, 35, 48]],
            ['name' => 'Driver 3', 'color' => 'orange', 'totals' => [0, 5, 5, 18]],
        );

        $view = DriverStandingsView::instance(
            new StandingsViewId('3cd830ac-9211-4e83-b863-df318b58ee59'),
            new StandingsViewChampionshipSlug('formula1'),
            new StandingsViewYear(2023),
            $events,
            $standings,
        );

        $repository = new DriverStandingsViewsRepository(self::connection());

        $repository->save($view);

        self::assertAggregateRootWasSaved($view);

        $saved = $repository->findOne('formula1', 2023);

        self::assertNotNull($saved);

        self::assertEqualsCanonicalizing(['fr', 'gb', 'es', 'en'], $saved->events()->value());

        self::assertEqualsCanonicalizing(
            [
                ['name' => 'Driver 1', 'color' => 'white', 'totals' => [0, 10, 15, 25]],
                ['name' => 'Driver 2', 'color' => 'black', 'totals' => [7, 15, 35, 48]],
                ['name' => 'Driver 3', 'color' => 'orange', 'totals' => [0, 5, 5, 18]],
            ],
            $saved->standings()->value(),
        );
    }
}
