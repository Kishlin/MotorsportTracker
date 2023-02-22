<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;

interface TeamPresentationGateway
{
    public function save(TeamPresentation $teamPresentation): void;
}
