<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence;

use LogicException;

/**
 * @property ObjectStoreSpy $objectStore
 */
trait SeasonFinderTrait
{
    protected function findSeasonIdOrStop(string $championship, int $year): string
    {
        $seriesId = $this->findSeriesIdOrStop($championship);

        foreach ($this->objectStore->all('season') as $season) {
            if ($season['year'] === $year && $season['series'] === $seriesId) {
                return $season['id'];
            }
        }

        throw new LogicException("Season {$championship} {$year} not found");
    }

    protected function findSeriesIdOrStop(string $championship): string
    {
        $series = $this->findSeriesId($championship);

        if (null === $series) {
            throw new LogicException("Series {$championship} not found");
        }

        return $series;
    }

    protected function findSeriesId(string $championship): ?string
    {
        foreach ($this->objectStore->all('series') as $series) {
            if ($series['name'] === $championship) {
                return $series['id'];
            }
        }

        return null;
    }
}
