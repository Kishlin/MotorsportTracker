<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationName;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class TeamPresentation extends AggregateRoot
{
    public function __construct(
        private readonly TeamPresentationId $id,
        private readonly TeamPresentationImage $image,
        private readonly TeamPresentationName $name,
        private readonly TeamPresentationTeamId $teamId,
    ) {
    }

    public static function create(
        TeamPresentationId $id,
        TeamPresentationImage $image,
        TeamPresentationName $name,
        TeamPresentationTeamId $teamId,
    ): self {
        return new self($id, $image, $name, $teamId);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        TeamPresentationId $id,
        TeamPresentationImage $image,
        TeamPresentationName $name,
        TeamPresentationTeamId $teamId,
    ): self {
        return new self($id, $image, $name, $teamId);
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
}
