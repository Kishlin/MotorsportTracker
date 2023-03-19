<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsIfNotExists\SearchAnalyticsRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsIfNotExists\SearchAnalyticsRepository
 */
final class SearchAnalyticsRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindAnalytics(): void
    {
        self::loadFixture('motorsport.standing.analytics.maxVerstappen2022');

        $repository = new SearchAnalyticsRepository(self::connection());

        $analyticsId = $repository->find(
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new UuidValueObject(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
        );

        self::assertNotNull($analyticsId);
        self::assertSame(self::fixtureId('motorsport.standing.analytics.maxVerstappen2022'), $analyticsId->value());
    }

    public function testItReturnsNullWhenAnalyticsAreNotFound(): void
    {
        $repository = new SearchAnalyticsRepository(self::connection());

        self::assertNull(
            $repository->find(
                new UuidValueObject('d0fd2f2b-156c-4c36-9afb-6bef0ffc170e'),
                new UuidValueObject('eb1011d2-6f05-4104-9ba5-a0b2b6256433'),
            ),
        );
    }
}
