<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsIfNotExists\SaveAnalyticsDriversRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsIfNotExists\SaveAnalyticsDriversRepository
 */
final class SaveAnalyticsRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnalytics(): void
    {
        $analytics = AnalyticsDrivers::create(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::uuid()),
            new PositiveIntValueObject(3),
            new FloatValueObject(415.2),
            AnalyticsStatsDTO::fromScalars(
                2.71,
                5,
                7,
                3,
                2,
                9,
                12,
                14,
                2,
                1,
                5,
                8,
                22,
                18,
                16,
                11,
                50.0,
            ),
        );

        $repository = new SaveAnalyticsDriversRepository(self::connection());

        $repository->save($analytics);

        self::assertAggregateRootWasSaved($analytics);
    }
}
