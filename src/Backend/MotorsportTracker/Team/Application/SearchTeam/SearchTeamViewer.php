<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;

interface SearchTeamViewer
{
    public function search(string $keyword): ?TeamId;
}
