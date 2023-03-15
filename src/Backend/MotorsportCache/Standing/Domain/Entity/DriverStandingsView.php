<?php
/**
* @noinspection PhpMultipleClassDeclarationsInspection
* @noinspection DuplicatedCode
*/

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Entity;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewYear;
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
     * @param array{
     *     id: string,
     *     championship_slug: string,
     *     year: int,
     *     events: string,
     *     standings: string,
     * } $data
     *
     * @throws JsonException
     */
    public static function fromData(array $data): self
    {
        return new self(
            new StandingsViewId($data['id']),
            new StandingsViewChampionshipSlug($data['championship_slug']),
            new StandingsViewYear($data['year']),
            StandingsViewEvents::fromString($data['events']),
            StandingsViewStandings::fromString($data['standings']),
        );
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

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'                => $this->id->value(),
            'championship_slug' => $this->championshipSlug->value(),
            'year'              => $this->year->value(),
            'events'            => $this->events->asString(),
            'standings'         => $this->standings->asString(),
        ];
    }
}
