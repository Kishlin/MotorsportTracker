<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStandingsView;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStandingsView
 */
final class TeamStandingsViewTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id               = '3cd830ac-9211-4e83-b863-df318b58ee59';
        $championshipSlug = 'formula1';
        $year             = 2023;
        $events           = ['fr', 'gb', 'es', 'en'];
        $standings        = [
            ['team' => 'Team 1', 'color' => 'white', 'totals' => [0, 10, 15, 25]],
            ['team' => 'Team 2', 'color' => 'black', 'totals' => [7, 15, 35, 48]],
            ['team' => 'Team 3', 'color' => 'orange', 'totals' => [0, 5, 5, 18]],
        ];

        $entity = TeamStandingsView::instance(
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
