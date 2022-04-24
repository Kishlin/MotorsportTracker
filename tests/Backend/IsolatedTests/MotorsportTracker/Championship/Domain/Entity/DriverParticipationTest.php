<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\DriverParticipationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\DriverParticipation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\DriverParticipationDriverId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\DriverParticipationId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\DriverParticipationSeasonId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\DriverParticipation
 */
final class DriverParticipationTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id       = '8aacc185-3ad4-45e4-abb0-db4c3f74a4e3';
        $seasonId = 'bdbd2561-7348-468b-beab-73d58ecdab5b';
        $driverId = '06122ec7-8550-4979-b958-cd05cdabeccc';

        $entity = DriverParticipation::create(
            new DriverParticipationId($id),
            new DriverParticipationSeasonId($seasonId),
            new DriverParticipationDriverId($driverId),
        );

        self::assertItRecordedDomainEvents($entity, new DriverParticipationCreatedDomainEvent(
            new DriverParticipationId($id),
        ));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($driverId, $entity->driverId());
        self::assertValueObjectSame($seasonId, $entity->seasonId());
    }
}
