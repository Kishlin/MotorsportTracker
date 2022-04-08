<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Championship\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship
 */
final class ChampionshipTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id   = 'bec0ec9f-e814-4dd6-bc77-d08e6ca17023';
        $name = 'championship';

        $entity = Championship::create(new ChampionshipId($id), new ChampionshipName($name));

        self::assertItRecordedDomainEvents($entity, new ChampionshipCreatedDomainEvent(new ChampionshipId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
    }
}
