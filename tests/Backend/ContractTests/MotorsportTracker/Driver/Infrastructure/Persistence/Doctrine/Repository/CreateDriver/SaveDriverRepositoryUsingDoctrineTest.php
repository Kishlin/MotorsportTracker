<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriver\SaveDriverRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriver\SaveDriverRepositoryUsingDoctrine
 */
final class SaveDriverRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixture('country.country.netherlands');

        $driver = Driver::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Max Verstappen'),
            new UuidValueObject(self::fixtureId('country.country.netherlands')),
        );

        $repository = new SaveDriverRepositoryUsingDoctrine(self::entityManager());

        $repository->save($driver);

        self::assertAggregateRootWasSaved($driver);
    }
}
