<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists\SearchDriverRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists\SearchDriverRepositoryUsingDoctrine
 */
final class SearchDriverRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindADriver(): void
    {
        self::loadFixture('motorsport.driver.driver.maxVerstappen');

        $repository = new SearchDriverRepositoryUsingDoctrine(self::connection());

        self::assertSame(
            self::fixtureId('motorsport.driver.driver.maxVerstappen'),
            $repository->findByNameOrRef(new StringValueObject('Max Verstappen'), new NullableUuidValueObject(null))?->value(),
        );
    }

    public function testItReturnsNullWhenDriverIsNotFound(): void
    {
        $repository = new SearchDriverRepositoryUsingDoctrine(self::connection());

        self::assertNull($repository->findByNameOrRef(new StringValueObject('Max Verstappen'), new NullableUuidValueObject(null)));
    }
}
