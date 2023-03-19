<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists\SaveAnalyticsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists\SearchAnalyticsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Analytics;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use RuntimeException;

/**
 * @property Analytics[] $objects
 *
 * @method Analytics[]    all()
 * @method null|Analytics get(UuidValueObject $id)
 * @method Analytics      safeGet(UuidValueObject $id)
 */
final class AnalyticsRepositorySpy extends AbstractRepositorySpy implements SaveAnalyticsGateway, SearchAnalyticsGateway
{
    public function save(Analytics $analytics): void
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
