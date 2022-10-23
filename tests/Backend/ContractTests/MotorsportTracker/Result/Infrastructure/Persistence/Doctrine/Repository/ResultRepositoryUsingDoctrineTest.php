<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultEventStepId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultFastestLapTime;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultRacerId;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\Repository\ResultRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\Repository\ResultRepositoryUsingDoctrine
 */
final class ResultRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAnEntity(): void
    {
        self::loadFixtures(
            'motorsport.racer.racer.verstappenAtRedBullRacingIn2022',
            'motorsport.event.eventStep.dutchGrandPrix2022Race',
        );

        $result = Result::instance(
            new ResultId(self::uuid()),
            new ResultRacerId(self::fixtureId('motorsport.racer.racer.verstappenAtRedBullRacingIn2022')),
            new ResultEventStepId(self::fixtureId('motorsport.event.eventStep.dutchGrandPrix2022Race')),
            new ResultFastestLapTime("1:36'42.773"),
            new ResultPosition(0),
            new ResultPoints(26),
        );

        $repository = new ResultRepositoryUsingDoctrine(self::entityManager());

        $repository->save($result);

        self::assertAggregateRootWasSaved($result);
    }
}
