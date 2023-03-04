<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SaveVenueGatewayUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SaveVenueGatewayUsingDoctrine
 */
final class VenueGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixture('country.country.netherlands');

        $venue = Venue::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Circuit Zandvoort'),
            new StringValueObject('circuit-zandvoort'),
            new UuidValueObject(self::fixtureId('country.country.netherlands')),
        );

        $repository = new SaveVenueGatewayUsingDoctrine(self::entityManager());

        $repository->save($venue);

        self::assertAggregateRootWasSaved($venue);
    }
}
