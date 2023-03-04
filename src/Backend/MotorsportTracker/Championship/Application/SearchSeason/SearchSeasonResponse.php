<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SearchSeasonResponse implements Response
{
    private function __construct(
        private readonly UuidValueObject $seasonId,
    ) {
    }

    public function seasonId(): UuidValueObject
    {
        return $this->seasonId;
    }

    public static function fromScalar(UuidValueObject $seasonId): self
    {
        return new self($seasonId);
    }
}
