<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists\SearchTeamRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists\SearchTeamRepositoryUsingDoctrine
 */
final class SearchTeamRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItCanFindATeam(): void
    {
        self::loadFixture('motorsport.team.team.redBullRacing');

        $repository = new SearchTeamRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            self::fixtureId('motorsport.team.team.redBullRacing'),
            $repository->findBySlug('red-bull-racing')?->value(),
        );
    }

    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItReturnsNullWhenTeamIsNotFound(): void
    {
        $repository = new SearchTeamRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->findBySlug('red-bull-racing'));
    }
}
