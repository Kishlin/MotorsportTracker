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
            'event_session.dutch_grand_prix_2022_race',
        );

        $repository = new SessionsToComputeRepository(self::connection());

        $races = $repository->findSessions(self::fixtureId('event.dutch_grand_prix_2022'))->sessions();

        self::assertIsArray($races);
        self::assertCount(1, $races);

        self::assertSame('race', $races[0]['type']);
        self::assertSame(self::fixtureId('event_session.dutch_grand_prix_2022_race'), $races[0]['id']);
    }

    public function testItCanFindMultipleRacesToCompute(): void
    {
        self::loadFixtures(
            'event_session.australian_grand_prix_2023_formula_two_race_one',
            'event_session.australian_grand_prix_2023_formula_two_race_two',
        );

        $repository = new SessionsToComputeRepository(self::connection());

        $races = $repository->findSessions(self::fixtureId('event.australian_gp_2023_formula_two'))->sessions();

        self::assertIsArray($races);
        self::assertCount(2, $races);

        self::assertSame('race', $races[0]['type']);
        self::assertSame(self::fixtureId('event_session.australian_grand_prix_2023_formula_two_race_two'), $races[0]['id']);

        self::assertSame('race', $races[1]['type']);
        self::assertSame(self::fixtureId('event_session.australian_grand_prix_2023_formula_two_race_one'), $races[1]['id']);
    }

    public function testItIsEmptyWhenThereAreNone(): void
    {
        $repository = new SessionsToComputeRepository(self::connection());

        self::assertEmpty($repository->findSessions(self::uuid())->sessions());
    }
}
