<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use JsonException;
use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\UpdateEventResultsCache\SessionClassificationRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\UpdateEventResultsCache\SessionClassificationRepository
 */
final class SessionClassificationRepositoryTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws JsonException
     */
    public function testItCanFindResultsForOneSession(): void
    {
        self::loadFixtures(
            'motorsport.result.classification.maxVerstappenAtDutchGP2022Race',
            'motorsport.result.classification.sergioPerezAtDutchGP2022Race',
            'motorsport.team.team.redBullRacing'
        );

        $repository = new SessionClassificationRepository(self::connection());

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
        $repository = new SessionClassificationRepository(self::connection());

        self::assertEmpty($repository->findResult(self::uuid())->results());
    }
}