<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationName;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationTeamId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateTeamPresentationCommand implements Command
{
    private function __construct(
        private readonly string $teamId,
        private readonly string $name,
        private readonly string $image,
    ) {
    }

    public function teamId(): TeamPresentationTeamId
    {
        return new TeamPresentationTeamId($this->teamId);
    }

    public function name(): TeamPresentationName
    {
        return new TeamPresentationName($this->name);
    }

    public function image(): TeamPresentationImage
    {
        return new TeamPresentationImage($this->image);
    }

    public static function fromScalars(string $teamId, string $name, string $image): self
    {
        return new self($teamId, $name, $image);
    }
}
