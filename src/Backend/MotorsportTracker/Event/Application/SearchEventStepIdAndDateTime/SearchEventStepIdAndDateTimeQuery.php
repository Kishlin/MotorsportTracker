<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchEventStepIdAndDateTimeQuery implements Query
{
    private function __construct(
        private string $seasonId,
        private string $keyword,
        private string $eventType,
    ) {
    }

    public function seasonId(): string
    {
        return $this->seasonId;
    }

    public function keyword(): string
    {
        return $this->keyword;
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    public static function fromScalars(string $seasonId, string $keyword, string $eventType): self
    {
        return new self($seasonId, $keyword, $eventType);
    }
}
