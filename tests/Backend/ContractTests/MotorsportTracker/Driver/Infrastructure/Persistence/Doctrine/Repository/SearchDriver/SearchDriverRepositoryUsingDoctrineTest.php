<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriver;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriver\SearchDriverRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriver\SearchDriverRepositoryUsingDoctrine
 */
final class SearchDriverRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriver\SearchDriverRepositoryUsingDoctrineTest::testItCanFindADriverProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindADriver(string $driver): void
    {
        $this->loadFixture('motorsport.driver.driver.maxVerstappen');

        $repository = new SearchDriverRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            $this->fixtureId('motorsport.driver.driver.maxVerstappen'),
            $repository->search($driver)?->value(),
        );
    }

    /**
     * @return array<string , array{driver: string}>
     */
    public function testItCanFindADriverProvider(): array
    {
        return [
            'by full name'      => ['driver' => 'Max Verstappen'],
            'string formatting' => ['driver' => 'aXVer'],
            'by partial name'   => ['driver' => 'Max'],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResult(): void
    {
        $this->loadFixtures(
            'motorsport.driver.driver.maxVerstappen',
            'motorsport.driver.driver.lewisHamilton',
        );

        $repository = new SearchDriverRepositoryUsingDoctrine(self::entityManager());

        self::expectException(NonUniqueResultException::class);

        $repository->search('n');
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.driver.driver.maxVerstappen');

        $repository = new SearchDriverRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('Lewis Hamilton')); // does not exist
        self::assertNull($repository->search('Max Costcappen')); // typo
    }
}