<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Doctrine\Repository;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryGatewayUsingDoctrine
 */
final class CountryGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveACountry(): void
    {
        $country = Country::instance(
            new CountryId(self::uuid()),
            new CountryCode('fr'),
        );

        $repository = new CountryGatewayUsingDoctrine(self::entityManager());

        $repository->save($country);

        self::assertAggregateRootWasSaved($country);
    }
}
