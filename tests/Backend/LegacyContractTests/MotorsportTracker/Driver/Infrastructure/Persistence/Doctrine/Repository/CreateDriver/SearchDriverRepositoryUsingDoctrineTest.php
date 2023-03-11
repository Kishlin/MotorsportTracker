<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriver;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriverIfNotExists\SearchDriverRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriverIfNotExists\SearchDriverRepositoryUsingDoctrine
 */
final class SearchDriverRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItCanFindADriver(): void
    {
        self::loadFixture('motorsport.driver.driver.maxVerstappen');

        $repository = new SearchDriverRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            self::fixtureId('motorsport.driver.driver.maxVerstappen'),
            $repository->findBySlug('max-verstappen')?->value(),
        );
    }

    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItReturnsNullWhenDriverIsNotFound(): void
    {
        $repository = new SearchDriverRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->findBySlug('max-verstappen'));
    }
}
