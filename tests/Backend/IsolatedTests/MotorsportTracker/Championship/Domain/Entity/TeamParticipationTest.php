<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\TeamParticipationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\TeamParticipation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\TeamParticipationId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\TeamParticipationSeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\TeamParticipationTeamId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\TeamParticipation
 */
final class TeamParticipationTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id       = 'b743df07-4772-401c-b714-614149fb2ad3';
        $seasonId = 'cfd4455f-d1ab-48dc-b142-5507e0bee514';
        $driverId = '72298d3a-d44a-4f08-bb4a-6e07bbda235b';

        $entity = TeamParticipation::create(
            new TeamParticipationId($id),
            new TeamParticipationSeasonId($seasonId),
            new TeamParticipationTeamId($driverId),
        );

        self::assertItRecordedDomainEvents($entity, new TeamParticipationCreatedDomainEvent(
            new TeamParticipationId($id),
        ));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($driverId, $entity->teamId());
        self::assertValueObjectSame($seasonId, $entity->seasonId());
    }
}
