<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

final class TeamTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id   = '4f56cfcc-defa-4f9f-a65c-d4758303a706';
        $name = 'Team';

        $entity = Team::create(new TeamId($id), new TeamName($name));

        self::assertItRecordedDomainEvents($entity, new TeamCreatedDomainEvent(new TeamId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
    }
}
