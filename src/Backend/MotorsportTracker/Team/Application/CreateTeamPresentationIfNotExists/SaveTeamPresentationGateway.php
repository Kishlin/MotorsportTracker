<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;

interface SaveTeamPresentationGateway
{
    public function save(TeamPresentation $teamPresentation): void;
}
