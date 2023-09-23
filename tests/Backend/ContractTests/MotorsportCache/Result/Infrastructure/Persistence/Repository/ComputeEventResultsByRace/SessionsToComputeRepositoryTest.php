<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\UpdateEventResultsCache\SessionsToComputeRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\UpdateEventResultsCache\SessionsToComputeRepository
 */
final class SessionsToComputeRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindARaceToCompute(): void
    {
        self::loadFixtures(
            'motorsport.event.eventSession.dutchGrandPrix2022Race',
        );

        $repository = new SessionsToComputeRepository(self::connection());

        $races = $repository->findSessions(self::fixtureId('motorsport.event.event.dutchGrandPrix2022'))->sessions();

        self::assertIsArray($races);
        self::assertCount(1, $races);

        self::assertSame('race', $races[0]['type']);
        self::assertSame(self::fixtureId('motorsport.event.eventSession.dutchGrandPrix2022Race'), $races[0]['id']);
    }

    public function testItCanFindMultipleRacesToCompute(): void
    {
        self::loadFixtures(
            'motorsport.event.eventSession.australianGrandPrix2023FormulaTwoRaceOne',
            'motorsport.event.eventSession.australianGrandPrix2023FormulaTwoRaceTwo',
        );

        $repository = new SessionsToComputeRepository(self::connection());

        $races = $repository->findSessions(self::fixtureId('motorsport.event.event.australianGP2023FormulaTwo'))->sessions();

        self::assertIsArray($races);
        self::assertCount(2, $races);

        self::assertSame('race', $races[0]['type']);
        self::assertSame(self::fixtureId('motorsport.event.eventSession.australianGrandPrix2023FormulaTwoRaceTwo'), $races[0]['id']);

        self::assertSame('race', $races[1]['type']);
        self::assertSame(self::fixtureId('motorsport.event.eventSession.australianGrandPrix2023FormulaTwoRaceOne'), $races[1]['id']);
    }

    public function testItIsEmptyWhenThereAreNone(): void
    {
        $repository = new SessionsToComputeRepository(self::connection());

        self::assertEmpty($repository->findSessions(self::uuid())->sessions());
    }
}
