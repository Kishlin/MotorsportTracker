<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriverIfNotExists\SaveDriverRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriverIfNotExists\SaveDriverRepositoryUsingDoctrine
 */
final class SaveDriverRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixture('country.country.netherlands');

        $driver = Driver::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Max Verstappen'),
            new StringValueObject('max-verstappen'),
            new UuidValueObject(self::fixtureId('country.country.netherlands')),
        );

        $repository = new SaveDriverRepositoryUsingDoctrine(self::entityManager());

        $repository->save($driver);

        self::assertAggregateRootWasSaved($driver);
    }
}
