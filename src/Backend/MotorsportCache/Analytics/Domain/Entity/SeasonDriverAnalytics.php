<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\ValueObject\DriverAnalyticsView;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class SeasonDriverAnalytics implements CacheItem
{
    private function __construct(
        private DriverAnalyticsView $driverAnalyticsView,
    ) {
    }

    public static function create(DriverAnalyticsView $driverAnalyticsView): self
    {
        return new self($driverAnalyticsView);
    }

    public static function computeKey(string $championship, int $year): string
    {
        return "analytics-driver-{$championship}-{$year}";
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->driverAnalyticsView->value();
    }
}
