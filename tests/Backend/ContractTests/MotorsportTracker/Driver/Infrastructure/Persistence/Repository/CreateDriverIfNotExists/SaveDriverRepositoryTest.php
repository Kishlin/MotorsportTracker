<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists\SaveDriverRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists\SaveDriverRepository
 */
final class SaveDriverRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixture('country.country.netherlands');

        $driver = Driver::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Max Verstappen'),
            new StringValueObject('VER'),
            new UuidValueObject(self::fixtureId('country.country.netherlands')),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveDriverRepository(self::connection());

        $repository->save($driver);

        self::assertAggregateRootWasSaved($driver);
    }
}
