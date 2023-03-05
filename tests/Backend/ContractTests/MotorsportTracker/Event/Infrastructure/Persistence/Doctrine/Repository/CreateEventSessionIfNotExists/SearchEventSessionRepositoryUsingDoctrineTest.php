<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists\SearchEventSessionRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists\SearchEventSessionRepositoryUsingDoctrine
 */
final class SearchEventSessionRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTheIdWhenItExists(): void
    {
        self::loadFixture('motorsport.event.eventSession.dutchGrandPrix2022Race');

        $repository = new SearchEventSessionRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            self::fixtureId('motorsport.event.eventSession.dutchGrandPrix2022Race'),
            $repository->search('dutchGrandPrix2022Race')?->value(),
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new SearchEventSessionRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('slug'));
    }
}
