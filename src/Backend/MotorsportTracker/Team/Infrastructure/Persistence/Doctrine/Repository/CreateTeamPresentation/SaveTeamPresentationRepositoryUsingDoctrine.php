<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamPresentation;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\SaveTeamPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveTeamPresentationRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveTeamPresentationGateway
{
    public function save(TeamPresentation $teamPresentation): void
    {
        $this->persist($teamPresentation);
    }
}
