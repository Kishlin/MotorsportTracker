<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\SearchCountryGatewayUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\SearchCountryGatewayUsingDoctrine
 */
final class CountryIdForCodeGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('country.country.france');

        $repository = new SearchCountryGatewayUsingDoctrine($this->entityManager());

        self::assertNotNull($repository->searchForCode(new StringValueObject('fr')));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullIfCodeDoesNotExist(): void
    {
        $repository = new SearchCountryGatewayUsingDoctrine($this->entityManager());

        self::assertNull($repository->searchForCode(new StringValueObject('fr')));
    }
}
