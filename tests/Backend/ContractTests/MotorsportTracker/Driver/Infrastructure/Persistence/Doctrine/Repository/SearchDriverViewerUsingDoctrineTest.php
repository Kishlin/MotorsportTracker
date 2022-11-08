<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriverViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriverViewerUsingDoctrine
 */
final class SearchDriverViewerUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\SearchDriverViewerUsingDoctrineTest::testItCanFindADriverProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindADriver(string $driver): void
    {
        $this->loadFixture('motorsport.driver.driver.maxVerstappen');

        $repository = new SearchDriverViewerUsingDoctrine(self::entityManager());

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
            'by full name'    => ['driver' => 'Max Verstappen'],
            'by partial name' => ['driver' => 'Max'],
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

        $repository = new SearchDriverViewerUsingDoctrine(self::entityManager());

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

        $repository = new SearchDriverViewerUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('Lewis Hamilton')); // does not exist
        self::assertNull($repository->search('Max Costcappen')); // typo
    }
}
