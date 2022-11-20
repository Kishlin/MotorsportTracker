<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueViewerUsingDoctrine
 */
final class SearchVenueViewerUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository\SearchVenueViewerUsingDoctrineTest::testItCanFindAVenueProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindAVenue(string $venue): void
    {
        $this->loadFixture('motorsport.venue.venue.zandvoort');

        $repository = new SearchVenueViewerUsingDoctrine(self::entityManager());

        self::assertSame(
            $this->fixtureId('motorsport.venue.venue.zandvoort'),
            $repository->search($venue)?->value(),
        );
    }

    /**
     * @return array<string , array{venue: string}>
     */
    public function testItCanFindAVenueProvider(): array
    {
        return [
            'by full name'      => ['venue' => 'Circuit Zandvoort'],
            'string formatting' => ['venue' => 'zAnDvOoRt'],
            'by partial name'   => ['venue' => 'and'],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResult(): void
    {
        $this->loadFixtures(
            'motorsport.venue.venue.zandvoort',
            'motorsport.venue.venue.circuitOfTheAmericas',
        );

        $repository = new SearchVenueViewerUsingDoctrine(self::entityManager());

        self::expectException(NonUniqueResultException::class);

        $repository->search('circuit');
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.venue.venue.zandvoort');

        $repository = new SearchVenueViewerUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('Melbourne')); // does not exist
        self::assertNull($repository->search('zandvort')); // typo
    }
}
