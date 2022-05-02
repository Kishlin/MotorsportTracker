<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryIdForCodeGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryIdForCodeGatewayUsingDoctrine
 */
final class CountryIdForCodeGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItCanRetrieveAnId(): void
    {
        $country = CountryProvider::country();

        $this->loadFixtures($country);

        $repository = new CountryIdForCodeGatewayUsingDoctrine($this->entityManager());

        self::assertEqualsCanonicalizing($country->id(), $repository->idForCode($country->code()));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullIfCodeDoesNotExist(): void
    {
        $repository = new CountryIdForCodeGatewayUsingDoctrine($this->entityManager());

        self::assertNull($repository->idForCode(new CountryCode('fr')));
    }
}
