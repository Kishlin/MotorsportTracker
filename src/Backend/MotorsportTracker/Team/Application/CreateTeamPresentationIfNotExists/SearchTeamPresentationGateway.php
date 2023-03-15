<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchTeamPresentationGateway
{
    public function findByTeamAndSeason(UuidValueObject $team, UuidValueObject $season): ?UuidValueObject;
}
