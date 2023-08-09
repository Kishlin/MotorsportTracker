<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers
 */
final class AnalyticsTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id                   = '8ec5b564-4081-4ebb-af1c-745da345b274';
        $season               = '9d40cce9-7c77-4684-96f0-6765031a2563';
        $driver               = 'ba52366e-9f2e-4233-b41e-d1d4c20002d2';
        $country              = 'fad6d171-b529-4f41-b2c6-45c926e088d9';
        $position             = 3;
        $points               = 415.2;
        $avgFinishPosition    = 2.71;
        $classWins            = 5;
        $fastestLaps          = 7;
        $finalAppearances     = 3;
        $hatTricks            = 2;
        $podiums              = 9;
        $poles                = 12;
        $racesLed             = 14;
        $ralliesLed           = 2;
        $retirements          = 1;
        $semiFinalAppearances = 5;
        $stageWins            = 8;
        $starts               = 22;
        $top10s               = 18;
        $top5s                = 16;
        $wins                 = 11;
        $winsPercentage       = 50.0;

        $entity = AnalyticsDrivers::create(
            new UuidValueObject($id),
            new UuidValueObject($season),
            new UuidValueObject($driver),
            new UuidValueObject($country),
            new PositiveIntValueObject($position),
            new FloatValueObject($points),
            AnalyticsStatsDTO::fromScalars(
                $avgFinishPosition,
                $classWins,
                $fastestLaps,
                $finalAppearances,
                $hatTricks,
                $podiums,
                $poles,
                $racesLed,
                $ralliesLed,
                $retirements,
                $semiFinalAppearances,
                $stageWins,
                $starts,
                $top10s,
                $top5s,
                $wins,
                $winsPercentage,
            ),
        );

        self::assertItRecordedDomainEvents($entity, new AnalyticsCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($season, $entity->season());
        self::assertValueObjectSame($driver, $entity->driver());
        self::assertValueObjectSame($country, $entity->country());
        self::assertValueObjectSame($position, $entity->position());
        self::assertValueObjectSame($points, $entity->points());
        self::assertValueObjectSame($avgFinishPosition, $entity->avgFinishPosition());
        self::assertValueObjectSame($classWins, $entity->classWins());
        self::assertValueObjectSame($fastestLaps, $entity->fastestLaps());
        self::assertValueObjectSame($finalAppearances, $entity->finalAppearances());
        self::assertValueObjectSame($hatTricks, $entity->hatTricks());
        self::assertValueObjectSame($podiums, $entity->podiums());
        self::assertValueObjectSame($poles, $entity->poles());
        self::assertValueObjectSame($racesLed, $entity->racesLed());
        self::assertValueObjectSame($ralliesLed, $entity->ralliesLed());
        self::assertValueObjectSame($retirements, $entity->retirements());
        self::assertValueObjectSame($semiFinalAppearances, $entity->semiFinalAppearances());
        self::assertValueObjectSame($stageWins, $entity->stageWins());
        self::assertValueObjectSame($starts, $entity->starts());
        self::assertValueObjectSame($top10s, $entity->top10s());
        self::assertValueObjectSame($top5s, $entity->top5s());
        self::assertValueObjectSame($wins, $entity->wins());
        self::assertValueObjectSame($winsPercentage, $entity->winsPercentage());
    }
}
