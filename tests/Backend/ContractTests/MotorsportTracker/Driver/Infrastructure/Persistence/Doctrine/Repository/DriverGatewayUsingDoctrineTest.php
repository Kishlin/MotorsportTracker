<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\DriverGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Driver\DriverProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @coversNothing
 */
final class DriverGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixtures(CountryProvider::netherlands());

        $driver = DriverProvider::dutchDriver();

        $repository = new DriverGatewayUsingDoctrine(self::entityManager());

        $repository->save($driver);

        self::assertAggregateRootWasSaved($driver);
    }
}
