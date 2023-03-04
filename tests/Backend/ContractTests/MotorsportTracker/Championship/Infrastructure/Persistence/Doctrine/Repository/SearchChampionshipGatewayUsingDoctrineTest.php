<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchChampionshipGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchChampionshipGatewayUsingDoctrine
 */
final class SearchChampionshipGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItCanFindAChampionship(): void
    {
        self::loadFixture('motorsport.championship.championship.formulaOne');

        $repository = new SearchChampionshipGatewayUsingDoctrine(self::entityManager());

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
        $repository = new SearchChampionshipGatewayUsingDoctrine(self::entityManager());

        self::assertNull($repository->findBySlug('formula1'));
    }
}
