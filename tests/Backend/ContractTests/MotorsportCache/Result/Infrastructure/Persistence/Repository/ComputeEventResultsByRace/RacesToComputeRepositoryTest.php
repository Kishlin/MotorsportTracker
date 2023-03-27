<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace\RacesToComputeRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace\RacesToComputeRepository
 */
final class RacesToComputeRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindARaceToCompute(): void
    {
        self::loadFixtures(
            'motorsport.event.eventSession.dutchGrandPrix2022Race',
        );

        $repository = new RacesToComputeRepository(self::connection());

        $races = $repository->findRaces(self::fixtureId('motorsport.event.event.dutchGrandPrix2022'))->races();

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

        $repository = new RacesToComputeRepository(self::connection());

        $races = $repository->findRaces(self::fixtureId('motorsport.event.event.australianGP2023FormulaTwo'))->races();

        self::assertIsArray($races);
        self::assertCount(2, $races);

        self::assertSame('race', $races[0]['type']);
        self::assertSame(self::fixtureId('motorsport.event.eventSession.australianGrandPrix2023FormulaTwoRaceOne'), $races[0]['id']);

        self::assertSame('race', $races[1]['type']);
        self::assertSame(self::fixtureId('motorsport.event.eventSession.australianGrandPrix2023FormulaTwoRaceTwo'), $races[1]['id']);
    }

    public function testItIsEmptyWhenThereAreNone(): void
    {
        $repository = new RacesToComputeRepository(self::connection());

        self::assertEmpty($repository->findRaces(self::uuid())->races());
    }
}
