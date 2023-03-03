<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\VenueGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\VenueGatewayUsingDoctrine
 */
final class VenueGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixture('country.country.netherlands');

        $venue = Venue::instance(
            new VenueId(self::uuid()),
            new VenueName('Circuit Zandvoort'),
            new VenueCountryId(self::fixtureId('country.country.netherlands')),
        );

        $repository = new VenueGatewayUsingDoctrine(self::entityManager());

        $repository->save($venue);

        self::assertAggregateRootWasSaved($venue);
    }
}
