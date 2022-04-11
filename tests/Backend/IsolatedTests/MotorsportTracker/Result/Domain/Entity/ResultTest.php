<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ResultCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultDriverId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultEventStepId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultFastestLapTime;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultTeamId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result
 */
final class ResultTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id          = '23d86bca-61e2-4c40-aa32-57c572a8c65a';
        $teamId      = '363e2944-ae6d-4b6d-8ab6-dc2c7e00ab0e';
        $driverId    = '9cb772c5-b36f-4c6f-ad09-d4f78e389945';
        $eventStepId = 'aee5ef8d-9f65-4104-acd5-8d432c6b5c9d';
        $fastestLap  = "1'31.634";
        $position    = 3;
        $points      = 15;

        $entity = Result::create(
            new ResultId($id),
            new ResultTeamId($teamId),
            new ResultDriverId($driverId),
            new ResultEventStepId($eventStepId),
            new ResultFastestLapTime($fastestLap),
            new ResultPosition($position),
            new ResultPoints($points),
        );

        self::assertItRecordedDomainEvents($entity, new ResultCreatedDomainEvent(new ResultId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($teamId, $entity->teamId());
        self::assertValueObjectSame($driverId, $entity->driverId());
        self::assertValueObjectSame($eventStepId, $entity->eventStepId());
        self::assertValueObjectSame($fastestLap, $entity->fastestLapTime());
        self::assertValueObjectSame($position, $entity->position());
        self::assertValueObjectSame($points, $entity->points());
    }
}
