<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;

final class TeamProvider
{
    public static function redBullRacing(): Team
    {
        return Team::instance(
            new TeamId('aee290c9-3ab0-4a9a-a707-8756f9a7760f'),
            new TeamName('Red Bull Racing'),
            new TeamImage('https://example.com/redbullracing.webp'),
            new TeamCountryId('d5e6f0ce-eb8c-4da0-b071-31466027f32d'),
        );
    }
}
