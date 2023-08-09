<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\ValueObject\AnalyticsView;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class SeasonAnalytics implements CacheItem
{
    private function __construct(
        private AnalyticsView $analyticsView,
    ) {
    }

    public static function create(AnalyticsView $analyticsView): self
    {
        return new self($analyticsView);
    }

    public static function computeKey(string $type, string $championship, int $year): string
    {
        return "analytics-{$type}-{$championship}-{$year}";
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->analyticsView->value();
    }
}
