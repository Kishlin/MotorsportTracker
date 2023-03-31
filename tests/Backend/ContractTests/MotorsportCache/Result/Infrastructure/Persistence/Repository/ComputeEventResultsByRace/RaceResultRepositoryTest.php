<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use JsonException;
use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace\RaceResultRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace\RaceResultRepository
 */
final class RaceResultRepositoryTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws JsonException
     */
    public function testItCanFindResultsForOneSession(): void
    {
        self::loadFixtures(
            'motorsport.result.classification.maxVerstappenAtDutchGP2022Race',
            'motorsport.result.classification.sergioPerezAtDutchGP2022Race',
            'motorsport.team.teamPresentation.redBullRacing2022'
        );

        $repository = new RaceResultRepository(self::connection());

        $results = $repository->findResult(self::fixtureId('motorsport.event.eventSession.dutchGrandPrix2022Race'))->results();

        self::assertIsArray($results);
        self::assertCount(2, $results);

        self::assertSame(11, $results[0]['car_number']);
        self::assertSame('Sergio Perez', $results[0]['driver']['name']);

        self::assertSame(33, $results[1]['car_number']);
        self::assertSame('Max Verstappen', $results[1]['driver']['name']);
    }

    /**
     * @throws JsonException
     */
    public function testItIsEmptyWhenThereAreNone(): void
    {
        $repository = new RaceResultRepository(self::connection());

        self::assertEmpty($repository->findResult(self::uuid())->results());
    }
}
