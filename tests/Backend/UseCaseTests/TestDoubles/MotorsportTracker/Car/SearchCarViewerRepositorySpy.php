<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarViewer;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;

final class SearchCarViewerRepositorySpy implements SearchCarViewer
{
    public function __construct(
        private CarRepositorySpy $carRepositorySpy,
        private TeamRepositorySpy $teamRepositorySpy,
        private SaveSeasonRepositorySpy $seasonRepositorySpy,
        private ChampionshipRepositorySpy $championshipRepositorySpy,
    ) {
    }

    public function search(int $number, string $team, string $championship, int $year): ?CarId
    {
        foreach ($this->carRepositorySpy->all() as $car) {
            if ($car->number()->value() !== $number) {
                continue;
            }

            $carTeam = $this->teamRepositorySpy->safeGet($car->teamId());

            if (false === $this->stringsMatch($carTeam->name(), $team)) {
                continue;
            }

            $carSeason = $this->seasonRepositorySpy->safeGet($car->seasonId());

            if ($carSeason->year()->value() !== $year) {
                continue;
            }

            $carChampionship = $this->championshipRepositorySpy->safeGet($carSeason->championshipId());

            if ($this->stringsMatch($carChampionship->name(), $championship)
                || $this->stringsMatch($carChampionship->slug(), $championship)
            ) {
                return $car->id();
            }
        }

        return null;
    }

    private function stringsMatch(StringValueObject $a, string $b): bool
    {
        return str_contains($this->format($a->value()), $this->format($b));
    }

    private function format(string $string): string
    {
        return strtolower(str_replace(' ', '', $string));
    }
}
