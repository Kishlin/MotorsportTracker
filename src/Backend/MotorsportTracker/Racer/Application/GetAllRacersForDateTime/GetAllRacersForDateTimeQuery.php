<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class GetAllRacersForDateTimeQuery implements Query
{
    private function __construct(
        private string $datetime,
        private string $seasonId,
    ) {
    }

    /**
     * @throws Exception
     */
    public function datetime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->datetime);
    }

    public function seasonId(): SeasonId
    {
        return new SeasonId($this->seasonId);
    }

    public static function fromScalars(string $dateTime, string $seasonId): self
    {
        return new self($dateTime, $seasonId);
    }
}
