<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsBySession;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class EventResultsByRace implements CacheItem
{
    private function __construct(
        private ResultsBySession $resultsBySession,
    ) {
    }

    public static function create(ResultsBySession $resultsByRace): self
    {
        return new self($resultsByRace);
    }

    public static function computeKey(string $eventId): string
    {
        return "event-results-{$eventId}";
    }

    public function resultsBySession(): ResultsBySession
    {
        return $this->resultsBySession;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->resultsBySession->value();
    }
}
