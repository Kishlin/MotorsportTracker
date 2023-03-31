<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Result\Infrastructure\Persistence\Repository\ViewEventResultsByRace;

use JsonException;
use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ViewEventResultsByRace\ViewEventResultsByRaceRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ViewEventResultsByRace\ViewEventResultsByRaceRepository
 */
final class EventResultsByRaceRepositoryTest extends CacheRepositoryContractTestCase
{
    /**
     * @throws JsonException
     */
    public function testItReturnsAViewWithNoResultsWhenThereAreNone(): void
    {
        $event = '9aa2dee6-1c98-474a-a934-718423b9c615';

        $repository = new ViewEventResultsByRaceRepository(self::connection());

        $view = $repository->viewForEvent($event)->toArray();

        self::assertSame($event, $view['event']);

        self::assertIsArray($view['resultsByRace']);
        self::assertEmpty($view['resultsByRace']);
    }

    /**
     * @throws JsonException
     */
    public function testItReturnsACompleteView(): void
    {
        self::loadFixture('motorsport.result.eventResultsByRace.firstTwoAtFormulaOneBahrainGP2023');

        $repository = new ViewEventResultsByRaceRepository(self::connection());

        $view = $repository->viewForEvent('00109fe5-1f07-489b-8e41-a5d9ab992423')->toArray();

        self::assertIsArray($view['resultsByRace']);
        self::assertNotEmpty($view['resultsByRace']);

        self::assertSame('20170b3e-0881-441e-8138-1858d23a734d', $view['resultsByRace'][0]['session']['id']);
    }
}
