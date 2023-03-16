<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Repository;

use Kishlin\Backend\Country\Infrastructure\Persistence\Repository\SearchCountryRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Repository\SearchCountryRepository
 */
final class SearchCountryRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('country.country.france');

        $repository = new SearchCountryRepository($this->connection());

        self::assertNotNull($repository->searchForCode(new StringValueObject('fr')));
    }

    public function testItReturnsNullIfCodeDoesNotExist(): void
    {
        $repository = new SearchCountryRepository($this->connection());

        self::assertNull($repository->searchForCode(new StringValueObject('fr')));
    }
}
