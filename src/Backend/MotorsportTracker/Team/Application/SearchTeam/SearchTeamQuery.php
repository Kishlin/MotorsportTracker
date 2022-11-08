<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchTeamQuery implements Query
{
    private function __construct(
        private string $keyword,
    ) {
    }

    public function keyword(): string
    {
        return $this->keyword;
    }

    public static function fromScalars(string $keyword): self
    {
        return new self($keyword);
    }
}
