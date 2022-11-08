<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchTeamResponse implements Response
{
    private function __construct(
        private TeamId $teamId,
    ) {
    }

    public function teamId(): TeamId
    {
        return $this->teamId;
    }

    public static function fromScalar(TeamId $teamId): self
    {
        return new self($teamId);
    }
}
