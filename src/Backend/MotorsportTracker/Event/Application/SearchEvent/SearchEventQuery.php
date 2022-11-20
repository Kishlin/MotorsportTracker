<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchEventQuery implements Query
{
    private function __construct(
        private string $seasonId,
        private string $keyword,
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

    public static function fromScalars(string $seasonId, string $keyword): self
    {
        return new self($seasonId, $keyword);
    }
}
