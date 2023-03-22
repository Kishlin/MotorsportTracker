<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\DriverByNameRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\DriverByNameRepository
 */
final class DriverByNameRepositoryTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\DriverByNameRepositoryTest::driverSearchProvider()
     */
    public function testItCanFindADriverByName(string $fixture, string $keyword): void
    {
        self::loadFixture($fixture);

        $repository = new DriverByNameRepository(self::connection());

        $id = $repository->find(new StringValueObject($keyword));

        self::assertNotNull($id);
        self::assertSame(self::fixtureId($fixture), $id->value());
    }

    /**
     * @return array<array<int, string>>
     */
    public function driverSearchProvider(): array
    {
        return [
            ['motorsport.driver.driver.maxVerstappen', 'Max Verstappen'],
            ['motorsport.driver.driver.lewisHamilton', 'Lewis Hamilton'],
        ];
    }

    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new DriverByNameRepository(self::connection());

        self::assertNull($repository->find(new StringValueObject('test')));
        self::assertNull($repository->find(new StringValueObject('Max Verstappen')));
        self::assertNull($repository->find(new StringValueObject('Lewis Hamilton')));
    }
}
