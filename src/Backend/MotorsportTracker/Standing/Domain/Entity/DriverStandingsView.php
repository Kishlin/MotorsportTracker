<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class DriverStandingsView extends AggregateRoot
{
    private function __construct(
        private readonly StandingsViewId $id,
        private readonly StandingsViewChampionshipSlug $championshipSlug,
        private readonly StandingsViewYear $year,
        private readonly StandingsViewEvents $events,
        private readonly StandingsViewStandings $standings,
    ) {
    }

    public static function create(
        StandingsViewId $id,
        StandingsViewChampionshipSlug $championshipSlug,
        StandingsViewYear $year,
        StandingsViewEvents $events,
        StandingsViewStandings $standings,
    ): self {
        return new self($id, $championshipSlug, $year, $events, $standings);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        StandingsViewId $id,
        StandingsViewChampionshipSlug $championshipSlug,
        StandingsViewYear $year,
        StandingsViewEvents $events,
        StandingsViewStandings $standings,
    ): self {
        return new self($id, $championshipSlug, $year, $events, $standings);
    }

    public function id(): StandingsViewId
    {
        return $this->id;
    }

    public function championshipSlug(): StandingsViewChampionshipSlug
    {
        return $this->championshipSlug;
    }

    public function year(): StandingsViewYear
    {
        return $this->year;
    }

    public function events(): StandingsViewEvents
    {
        return $this->events;
    }

    public function standings(): StandingsViewStandings
    {
        return $this->standings;
    }
}
