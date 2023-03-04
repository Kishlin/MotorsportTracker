<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonViewer;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SearchSeasonRepositorySpy implements SearchSeasonViewer
{
    public function __construct(
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
        private readonly SaveSeasonRepositorySpy $seasonRepositorySpy,
    ) {
    }

    public function search(string $championship, int $year): ?UuidValueObject
    {
        foreach ($this->seasonRepositorySpy->all() as $season) {
            $seasonChampionship = $this->championshipRepositorySpy->safeGet($season->championshipId());

            if ($season->year()->value() !== $year) {
                continue;
            }

            if (str_contains($seasonChampionship->name()->value(), $championship)
                && str_contains($seasonChampionship->slug()->value(), $championship)
            ) {
                continue;
            }

            return $season->id();
        }

        return null;
    }
}
