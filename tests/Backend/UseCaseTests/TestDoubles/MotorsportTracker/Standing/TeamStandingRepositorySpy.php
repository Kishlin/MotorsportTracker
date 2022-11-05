<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\TeamStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property TeamStanding[] $objects
 *
 * @method TeamStanding[]    all()
 * @method null|TeamStanding get(TeamStandingId $id)
 */
final class TeamStandingRepositorySpy extends AbstractRepositorySpy implements TeamStandingGateway
{
    public function save(TeamStanding $teamStanding): void
    {
        $uniqueKey = $teamStanding->eventId()->value() . '-' . $teamStanding->teamId()->value();

        $this->objects[$uniqueKey] = $teamStanding;
    }
}
