<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Entry\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ClassificationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ClassificationTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id               = '5c585e09-65a1-40f5-ac55-75b1f4d58c3f';
        $entry            = 'd4842210-5777-4c57-84be-ceb991917025';
        $finishPosition   = 9;
        $gridPosition     = 20;
        $laps             = 57;
        $points           = 2.0;
        $time             = 5710489.0;
        $classifiedStatus = 'CLA';
        $averageLapSpeed  = 194.319;
        $fastestLapTime   = 95068.0;
        $timeToLead       = 73753.0;
        $timeToNext       = 1106.0;
        $lapsToLead       = 0;
        $lapsToNext       = 0;
        $bestLap          = 42;
        $bestTime         = 95068.0;

        $entity = Classification::create(
            new UuidValueObject($id),
            new UuidValueObject($entry),
            new PositiveIntValueObject($finishPosition),
            new StrictlyPositiveIntValueObject($gridPosition),
            new PositiveIntValueObject($laps),
            new PositiveFloatValueObject($points),
            new PositiveFloatValueObject($time),
            new StringValueObject($classifiedStatus),
            new PositiveFloatValueObject($averageLapSpeed),
            new PositiveFloatValueObject($fastestLapTime),
            new PositiveFloatValueObject($timeToLead),
            new PositiveFloatValueObject($timeToNext),
            new PositiveIntValueObject($lapsToLead),
            new PositiveIntValueObject($lapsToNext),
            new PositiveIntValueObject($bestLap),
            new PositiveFloatValueObject($bestTime),
            new BoolValueObject(false),
        );

        self::assertItRecordedDomainEvents($entity, new ClassificationCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($entry, $entity->entry());
        self::assertValueObjectSame($finishPosition, $entity->finishPosition());
        self::assertValueObjectSame($gridPosition, $entity->gridPosition());
        self::assertValueObjectSame($laps, $entity->laps());
        self::assertValueObjectSame($points, $entity->points());
        self::assertValueObjectSame($time, $entity->time());
        self::assertValueObjectSame($classifiedStatus, $entity->classifiedStatus());
        self::assertValueObjectSame($averageLapSpeed, $entity->averageLapSpeed());
        self::assertValueObjectSame($fastestLapTime, $entity->fastestLapTime());
        self::assertValueObjectSame($fastestLapTime, $entity->fastestLapTime());
        self::assertValueObjectSame($timeToLead, $entity->gapTimeToLead());
        self::assertValueObjectSame($timeToNext, $entity->gapTimeToNext());
        self::assertValueObjectSame($lapsToLead, $entity->gapLapsToLead());
        self::assertValueObjectSame($lapsToNext, $entity->gapLapsToNext());
        self::assertValueObjectSame($bestLap, $entity->bestLap());
        self::assertValueObjectSame($bestTime, $entity->bestTime());
        self::assertValueObjectSame(false, $entity->bestIsFastest());
    }
}
