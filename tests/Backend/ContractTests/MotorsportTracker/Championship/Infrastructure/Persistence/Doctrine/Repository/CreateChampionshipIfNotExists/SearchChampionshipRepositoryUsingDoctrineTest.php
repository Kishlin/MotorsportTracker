<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists\SearchChampionshipRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists\SearchChampionshipRepositoryUsingDoctrine
 */
final class SearchChampionshipRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItCanFindAChampionship(): void
    {
        self::loadFixture('motorsport.championship.championship.formulaOne');

        $repository = new SearchChampionshipRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            self::fixtureId('motorsport.championship.championship.formulaOne'),
            $repository->findBySlug('formula1')?->value(),
        );
    }

    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItReturnsNullWhenChampionshipIsNotFound(): void
    {
        $repository = new SearchChampionshipRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->findBySlug('formula1'));
    }
}
