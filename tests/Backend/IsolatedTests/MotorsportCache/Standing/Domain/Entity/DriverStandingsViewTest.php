<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportCache\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView
 */
final class DriverStandingsViewTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id               = '3cd830ac-9211-4e83-b863-df318b58ee59';
        $championshipSlug = 'formula1';
        $year             = 2023;
        $events           = ['fr', 'gb', 'es', 'en'];
        $standings        = [
            ['driver' => 'Driver 1', 'color' => 'white', 'totals' => [0, 10, 15, 25]],
            ['driver' => 'Driver 2', 'color' => 'black', 'totals' => [7, 15, 35, 48]],
            ['driver' => 'Driver 3', 'color' => 'orange', 'totals' => [0, 5, 5, 18]],
        ];

        $entity = DriverStandingsView::instance(
            new StandingsViewId($id),
            new StandingsViewChampionshipSlug($championshipSlug),
            new StandingsViewYear($year),
            new StandingsViewEvents($events),
            new StandingsViewStandings($standings),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($year, $entity->year());
        self::assertValueObjectSame($championshipSlug, $entity->championshipSlug());
        self::assertEqualsCanonicalizing($standings, $entity->standings()->value());
        self::assertEqualsCanonicalizing($events, $entity->events()->value());
    }
}
