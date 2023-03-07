<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindSeriesGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use RuntimeException;

final class FindSeriesRepositorySpy implements FindSeriesGateway
{
    public function __construct(
        private readonly ChampionshipPresentationRepositorySpy $championshipPresentationRepositorySpy,
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
        private readonly SaveSeasonRepositorySpy $seasonRepositorySpy,
    ) {
    }

    public function findForSlug(StringValueObject $seriesSlug, PositiveIntValueObject $year): ?CalendarEventSeries
    {
        foreach ($this->championshipRepositorySpy->all() as $championship) {
            if (false === $championship->slug()->equals($seriesSlug)) {
                continue;
            }

            foreach ($this->seasonRepositorySpy->all() as $season) {
                if (false === $season->year()->equals($year)) {
                    continue;
                }

                $presentation = $this->championshipPresentationRepositorySpy->latest($championship->id());
                if (null === $presentation) {
                    throw new RuntimeException('Could not find a matching Championship Presentation.');
                }

                return CalendarEventSeries::fromData([
                    'name'  => $championship->name()->value(),
                    'slug'  => $championship->slug()->value(),
                    'year'  => $season->year()->value(),
                    'icon'  => $presentation->icon()->value(),
                    'color' => $presentation->color()->value(),
                ]);
            }
        }

        return null;
    }
}
