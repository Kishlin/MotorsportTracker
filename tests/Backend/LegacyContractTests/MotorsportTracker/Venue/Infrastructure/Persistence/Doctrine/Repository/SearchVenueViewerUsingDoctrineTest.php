<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueGatewayUsingDoctrine
 */
final class SearchVenueViewerUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindAVenue(): void
    {
        $this->loadFixture('motorsport.venue.venue.zandvoort');

        $repository = new SearchVenueGatewayUsingDoctrine(self::entityManager());

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
        $repository = new SearchVenueGatewayUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('circuit-zandvoort'));
    }
}