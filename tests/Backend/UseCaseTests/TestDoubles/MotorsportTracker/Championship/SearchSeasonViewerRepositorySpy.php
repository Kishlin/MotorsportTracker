<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonViewer;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;

final class SearchSeasonViewerRepositorySpy implements SearchSeasonViewer
{
    public function __construct(
        private ChampionshipRepositorySpy $championshipRepositorySpy,
        private SeasonRepositorySpy $seasonRepositorySpy,
    ) {
    }

    public function search(string $championship, int $year): ?SeasonId
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
