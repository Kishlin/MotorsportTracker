<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\SearchChampionshipRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\SearchChampionshipRepository
 */
final class SearchChampionshipRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindAChampionship(): void
    {
        self::loadFixture('motorsport.championship.championship.formulaOne');

        $repository = new SearchChampionshipRepository(self::connection());

        self::assertSame(
            self::fixtureId('motorsport.championship.championship.formulaOne'),
            $repository->findIfExists(new StringValueObject('formula1'), new NullableUuidValueObject(null))?->value(),
        );
    }

    public function testItReturnsNullWhenChampionshipIsNotFound(): void
    {
        $repository = new SearchChampionshipRepository(self::connection());

        self::assertNull($repository->findIfExists(new StringValueObject('formula1'), new NullableUuidValueObject(null)));
    }
}
