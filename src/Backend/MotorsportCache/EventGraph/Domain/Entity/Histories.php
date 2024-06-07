<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\HistoriesValueObject;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class Histories implements CacheItem, Graph
{
    private function __construct(
        private HistoriesValueObject $histories,
    ) {}

    public static function create(HistoriesValueObject $histories): self
    {
        return new self($histories);
    }

    public static function computeKey(string $event): string
    {
        return "histories-{$event}";
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->histories->value();
    }
}
