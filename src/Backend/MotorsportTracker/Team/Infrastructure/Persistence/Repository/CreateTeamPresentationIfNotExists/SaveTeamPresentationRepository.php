<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\SaveTeamPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveTeamPresentationRepository extends CoreRepository implements SaveTeamPresentationGateway
{
    public function save(TeamPresentation $teamPresentation): void
    {
        parent::persist($teamPresentation);
    }
}
