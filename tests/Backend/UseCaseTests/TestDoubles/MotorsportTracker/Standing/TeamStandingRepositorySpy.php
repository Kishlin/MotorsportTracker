<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\TeamStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property TeamStanding[] $objects
 *
 * @method TeamStanding[]    all()
 * @method null|TeamStanding get(UuidValueObject $id)
 * @method TeamStanding      safeGet(UuidValueObject $id)
 */
final class TeamStandingRepositorySpy extends AbstractRepositorySpy implements TeamStandingGateway
{
    public function save(TeamStanding $teamStanding): void
    {
        $uniqueKey = $teamStanding->eventId()->value() . '-' . $teamStanding->teamId()->value();

        $this->objects[$uniqueKey] = $teamStanding;
    }

    public function find(TeamStandingTeamId $teamId, TeamStandingEventId $eventId): ?TeamStanding
    {
        foreach ($this->objects as $teamStanding) {
            if ($teamId->equals($teamStanding->teamId()) && $eventId->equals($teamStanding->eventId())) {
                return $teamStanding;
            }
        }

        return null;
    }
}
