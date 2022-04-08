<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\SeasonCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Season extends AggregateRoot
{
    private function __construct(
        private SeasonId $id,
        private SeasonYear $year,
        private SeasonChampionshipId $championshipId,
    ) {
    }
    
    public static function create(SeasonId $id, SeasonYear $year, SeasonChampionshipId $championshipId): self
    {
        $season = new self($id, $year, $championshipId);

        $season->record(new SeasonCreatedDomainEvent($id));

        return $season;
    }

    /**
     * @internal Only use to get a test object.
     */
    public static function instance(SeasonId $id, SeasonYear $year, SeasonChampionshipId $championshipId): self
    {
        return new self($id, $year, $championshipId);
    }

    public function id(): SeasonId
    {
        return $this->id;
    }

    public function year(): SeasonYear
    {
        return $this->year;
    }

    public function championshipId(): SeasonChampionshipId
    {
        return $this->championshipId;
    }
}
