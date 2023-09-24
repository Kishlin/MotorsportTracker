<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use LogicException;

/**
 * @property ObjectStoreSpy $objectStore
 */
trait SeasonFinderTrait
{
    protected function findSeasonOrStop(string $championship, int $year): Season
    {
        $series = $this->findSeriesOrStop($championship);
        $filter = new StrictlyPositiveIntValueObject($year);

        /** @var Season $season */
        foreach ($this->objectStore->all(Season::class) as $season) {
            if ($season->year()->equals($filter) && $season->championshipId()->equals($series->id())) {
                return $season;
            }
        }

        throw new LogicException("Season {$championship} {$year} not found");
    }

    protected function findSeriesOrStop(string $championship): Series
    {
        $series = $this->findSeries($championship);

        if (null === $series) {
            throw new LogicException("Series {$championship} not found");
        }

        return $series;
    }

    protected function findSeries(string $championship): ?Series
    {
        $filter = new StringValueObject($championship);

        /** @var Series $series */
        foreach ($this->objectStore->all(Series::class) as $series) {
            if ($series->name()->equals($filter)) {
                return $series;
            }
        }

        return null;
    }
}
