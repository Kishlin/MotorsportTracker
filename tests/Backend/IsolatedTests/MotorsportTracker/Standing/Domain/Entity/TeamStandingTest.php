<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding
 */
final class TeamStandingTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id      = '71f5d078-f4fe-4fe2-bb26-d0c3d606b085';
        $eventId = 'aed26d46-4050-4782-b7a1-0c85d0e4ba33';
        $teamId  = '575d92d6-6372-419a-87c8-477b794afcc9';
        $points  = 5.0;

        $entity = TeamStanding::create(
            new TeamStandingId($id),
            new TeamStandingEventId($eventId),
            new TeamStandingTeamId($teamId),
            new TeamStandingPoints($points),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($eventId, $entity->eventId());
        self::assertValueObjectSame($teamId, $entity->teamId());
        self::assertValueObjectSame($points, $entity->points());
    }
}
