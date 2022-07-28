<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateTeamCommand implements Command
{
    private function __construct(
        private string $countryId,
        private string $image,
        private string $name,
    ) {
    }

    public function countryId(): TeamCountryId
    {
        return new TeamCountryId($this->countryId);
    }

    public function image(): TeamImage
    {
        return new TeamImage($this->image);
    }

    public function name(): TeamName
    {
        return new TeamName($this->name);
    }

    public static function fromScalars(string $countryId, string $image, string $name): self
    {
        return new self($countryId, $image, $name);
    }
}
