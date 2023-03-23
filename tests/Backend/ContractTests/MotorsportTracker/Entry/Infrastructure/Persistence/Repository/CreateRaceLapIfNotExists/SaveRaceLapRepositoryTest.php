<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists\SaveRaceLapRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists\SaveRaceLapRepository
 */
final class SaveRaceLapRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveARaceLap(): void
    {
        self::loadFixture('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $raceLap = RaceLap::instance(
            new UuidValueObject('1230a0c2-8392-4e57-967f-9859b43601bd'),
            new UuidValueObject('45b56055-ab7b-4271-b6c3-07eef8753f76'),
            new StrictlyPositiveIntValueObject(1),
            new StrictlyPositiveIntValueObject(9),
            new BoolValueObject(false),
            new StrictlyPositiveIntValueObject(99745),
            new StrictlyPositiveIntValueObject(10957),
            new PositiveIntValueObject(0),
            new StrictlyPositiveIntValueObject(602),
            new PositiveIntValueObject(0),
            new TyreDetailsValueObject([['type' => 'S', 'wear' => 'u', 'laps' => 6]]),
        );

        $repository = new SaveRaceLapRepository(self::connection());

        $repository->save($raceLap);

        self::assertAggregateRootWasSaved($raceLap);
    }
}
