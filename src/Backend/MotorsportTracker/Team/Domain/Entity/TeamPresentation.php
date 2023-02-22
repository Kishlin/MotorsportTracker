<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamPresentationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationName;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class TeamPresentation extends AggregateRoot
{
    public function __construct(
        private readonly TeamPresentationId $id,
        private readonly TeamPresentationTeamId $teamId,
        private readonly TeamPresentationName $name,
        private readonly TeamPresentationImage $image,
        private readonly TeamPresentationCreatedOn $createdOn,
    ) {
    }

    public static function create(
        TeamPresentationId $id,
        TeamPresentationTeamId $teamId,
        TeamPresentationName $name,
        TeamPresentationImage $image,
        TeamPresentationCreatedOn $createdOn,
    ): self {
        $teamPresentation = new self($id, $teamId, $name, $image, $createdOn);

        $teamPresentation->record(new TeamPresentationCreatedDomainEvent($id));

        return $teamPresentation;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        TeamPresentationId $id,
        TeamPresentationTeamId $teamId,
        TeamPresentationName $name,
        TeamPresentationImage $image,
        TeamPresentationCreatedOn $createdOn,
    ): self {
        return new self($id, $teamId, $name, $image, $createdOn);
    }

    public function id(): TeamPresentationId
    {
        return $this->id;
    }

    public function image(): TeamPresentationImage
    {
        return $this->image;
    }

    public function name(): TeamPresentationName
    {
        return $this->name;
    }

    public function teamId(): TeamPresentationTeamId
    {
        return $this->teamId;
    }

    public function createdOn(): TeamPresentationCreatedOn
    {
        return $this->createdOn;
    }
}
