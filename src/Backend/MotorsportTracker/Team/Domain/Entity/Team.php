<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Team extends AggregateRoot
{
    public function __construct(
        private TeamId $id,
        private TeamName $name,
    ) {
    }

    public static function create(TeamId $id, TeamName $name): self
    {
        $team = new self($id, $name);

        $team->record(new TeamCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(TeamId $id, TeamName $name): self
    {
        return new self($id, $name);
    }

    public function id(): TeamId
    {
        return $this->id;
    }

    public function name(): TeamName
    {
        return $this->name;
    }
}
