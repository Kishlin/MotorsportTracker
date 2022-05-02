<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Doctrine\Repository;

use Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryGatewayUsingDoctrine
 */
final class CountryGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveACountry(): void
    {
        $country = CountryProvider::country();

        self::loadFixtures($country);

        $repository = new CountryGatewayUsingDoctrine(self::entityManager());

        $repository->save($country);

        self::assertAggregateRootWasSaved($country);
    }
}
