<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\VenueGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Venue\VenueProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\VenueGatewayUsingDoctrine
 */
final class VenueGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixtures(CountryProvider::netherlands());

        $venue = VenueProvider::dutchVenue();

        $repository = new VenueGatewayUsingDoctrine(self::entityManager());

        $repository->save($venue);

        self::assertAggregateRootWasSaved($venue);
    }
}
