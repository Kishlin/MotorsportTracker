<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchSeasonResponse implements Response
{
    private function __construct(
        private SeasonId $seasonId,
    ) {
    }

    public function seasonId(): SeasonId
    {
        return $this->seasonId;
    }

    public static function fromScalar(SeasonId $seasonId): self
    {
        return new self($seasonId);
    }
}
