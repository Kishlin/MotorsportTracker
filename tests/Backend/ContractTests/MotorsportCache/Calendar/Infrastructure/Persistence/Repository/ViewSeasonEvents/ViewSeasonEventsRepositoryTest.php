<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonEvents;

use JsonException;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\SeasonEventsNotFoundException;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonEvents\ViewSeasonEventsRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\SeasonEventsJsonableView
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonEvents\ViewSeasonEventsRepository
 */
final class ViewSeasonEventsRepositoryTest extends CacheRepositoryContractTestCase
{
    /**
     * @throws JsonException
     */
    public function testItCanViewEventsForASeason(): void
    {
        self::loadFixture('motorsport.calendar.seasonEvents.firstThreeEventsOfFormulaOne2022');

        $repository = new ViewSeasonEventsRepository(self::connection());

        $view = $repository->viewForSeason('formula-one', 2022)->toArray();

        self::assertArrayHasKey('bahrain-grand-prix', $view);
        self::assertArrayHasKey('saudi-arabian-grand-prix', $view);
        self::assertArrayHasKey('australian-grand-prix', $view);
    }

    /**
     * @throws JsonException
     */
    public function testItRaisesExceptionWhenItDoesNotFindAnything(): void
    {
        $repository = new ViewSeasonEventsRepository(self::connection());

        self::expectException(SeasonEventsNotFoundException::class);
        $repository->viewForSeason('formula-one', 2022)->toArray();
    }
}
