<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryIdForCodeGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository\CountryIdForCodeGatewayUsingDoctrine
 */
final class CountryIdForCodeGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('country.country.france');

        $repository = new CountryIdForCodeGatewayUsingDoctrine($this->entityManager());

        self::assertNotNull($repository->idForCode(new CountryCode('fr')));
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
