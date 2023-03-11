<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchChampionshipRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchChampionshipRepositoryUsingDoctrine
 */
final class SearchChampionshipRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
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
            $repository->findIfExists(new StringValueObject('formula1'), new NullableUuidValueObject(null))?->value(),
        );
    }

    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItReturnsNullWhenChampionshipIsNotFound(): void
    {
        $repository = new SearchChampionshipRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->findIfExists(new StringValueObject('formula1'), new NullableUuidValueObject(null)));
    }
}
