<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists\SaveAnalyticsDriversGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists\SearchAnalyticsDriversGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use RuntimeException;

/**
 * @property AnalyticsDrivers[] $objects
 *
 * @method AnalyticsDrivers[]    all()
 * @method null|AnalyticsDrivers get(UuidValueObject $id)
 * @method AnalyticsDrivers      safeGet(UuidValueObject $id)
 */
final class AnalyticsRepositorySpyDriversDrivers extends AbstractRepositorySpy implements SaveAnalyticsDriversGateway, SearchAnalyticsDriversGateway
{
    public function save(AnalyticsDrivers $analytics): void
    {
        if (null !== $this->find($analytics->season(), $analytics->driver())) {
            throw new RuntimeException('Duplicate.');
        }

        $this->objects[$analytics->id()->value()] = $analytics;
    }

    public function find(UuidValueObject $season, UuidValueObject $driver): ?UuidValueObject
    {
        foreach ($this->objects as $savedAnalytics) {
            if ($savedAnalytics->season()->equals($season) && $savedAnalytics->driver()->equals($driver)) {
                return $savedAnalytics->id();
            }
        }

        return null;
    }
}
