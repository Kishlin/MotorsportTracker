<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueViewerUsingDoctrine
 */
final class SearchVenueViewerUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindAVenue(): void
    {
        $this->loadFixture('motorsport.venue.venue.zandvoort');

        $repository = new SearchVenueViewerUsingDoctrine(self::entityManager());

        self::assertSame(
            $this->fixtureId('motorsport.venue.venue.zandvoort'),
            $repository->search('circuit-zandvoort')?->value(),
        );
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItIsNullWhenThereIsNoResult(): void
    {
        $repository = new SearchVenueViewerUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('circuit-zandvoort'));
    }
}
