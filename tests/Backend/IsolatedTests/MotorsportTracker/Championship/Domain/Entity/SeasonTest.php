<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\SeasonCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season
 */
final class SeasonTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $championshipId = 'a5c6fd99-8e28-4ca8-a698-78a366e8c1c0';

        $id   = 'bec0ec9f-e814-4dd6-bc77-d08e6ca17023';
        $year = 1993;

        $entity = Season::create(new SeasonId($id), new SeasonYear($year), new SeasonChampionshipId($championshipId));

        self::assertItRecordedDomainEvents($entity, new SeasonCreatedDomainEvent(new SeasonId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($year, $entity->year());
        self::assertValueObjectSame($championshipId, $entity->championshipId());
    }
}
