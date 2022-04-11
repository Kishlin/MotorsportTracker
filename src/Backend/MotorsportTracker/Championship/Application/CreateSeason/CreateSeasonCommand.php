<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateSeasonCommand implements Command
{
    private function __construct(
        private string $championshipId,
        private int $year,
    ) {
    }

    public function championshipId(): SeasonChampionshipId
    {
        return new SeasonChampionshipId($this->championshipId);
    }

    public function year(): SeasonYear
    {
        return new SeasonYear($this->year);
    }

    public static function fromScalars(string $championshipId, int $year): self
    {
        return new self($championshipId, $year);
    }
}
