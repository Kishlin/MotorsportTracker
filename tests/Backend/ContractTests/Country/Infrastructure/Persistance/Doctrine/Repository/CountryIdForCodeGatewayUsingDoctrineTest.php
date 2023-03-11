<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Doctrine\Repository;

use Kishlin\Backend\Country\Infrastructure\Persistence\Repository\SearchCountryGatewayUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Repository\SearchCountryGatewayUsingDoctrine
 */
final class CountryIdForCodeGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('country.country.france');

        $repository = new SearchCountryGatewayUsingDoctrine($this->connection());

        self::assertNotNull($repository->searchForCode(new StringValueObject('fr')));
    }

    public function testItReturnsNullIfCodeDoesNotExist(): void
    {
        $repository = new SearchCountryGatewayUsingDoctrine($this->connection());

        self::assertNull($repository->searchForCode(new StringValueObject('fr')));
    }
}
