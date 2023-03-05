<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists\SearchEventRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists\SearchEventRepositoryUsingDoctrine
 */
final class SearchEventRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTheIdWhenItExists(): void
    {
        self::loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new SearchEventRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            self::fixtureId('motorsport.event.event.dutchGrandPrix2022'),
            $repository->find('Dutch-gp')?->value(),
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new SearchEventRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->find('slug'));
    }
}
