<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Championship extends AggregateRoot
{
    private function __construct(
        private ChampionshipId $id,
        private ChampionshipName $name,
    ) {
    }

    public static function create(ChampionshipId $id, ChampionshipName $name): self
    {
        $championship = new self($id, $name);

        $championship->record(new ChampionshipCreatedDomainEvent($id));

        return $championship;
    }

    /**
     * @internal Only use to get a test object.
     */
    public static function instance(ChampionshipId $id, ChampionshipName $name): self
    {
        return new self($id, $name);
    }

    public function id(): ChampionshipId
    {
        return $this->id;
    }

    public function name(): ChampionshipName
    {
        return $this->name;
    }
}
