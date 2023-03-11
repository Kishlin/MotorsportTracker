<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Repository\SaveVenueGatewayUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Repository\SaveVenueGatewayUsingDoctrine
 */
final class VenueGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixture('country.country.netherlands');

        $venue = Venue::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Circuit Zandvoort'),
            new UuidValueObject(self::fixtureId('country.country.netherlands')),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveVenueGatewayUsingDoctrine(self::connection());

        $repository->save($venue);

        self::assertAggregateRootWasSaved($venue);
    }
}
