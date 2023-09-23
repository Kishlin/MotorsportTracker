<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists\SaveRaceLapRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
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
            new UuidValueObject(self::fixtureId('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race')),
            new PositiveIntValueObject(1),
            new PositiveIntValueObject(9),
            new BoolValueObject(false),
            new PositiveIntValueObject(99745),
            new NullableIntValueObject(10957),
            new NullableIntValueObject(0),
            new NullableIntValueObject(602),
            new NullableIntValueObject(0),
            new TyreDetailsValueObject([['type' => 'S', 'wear' => 'u', 'laps' => 6]]),
        );

        $repository = new SaveRaceLapRepository(self::connection());

        $repository->save($raceLap);

        self::assertAggregateRootWasSaved($raceLap);
    }
}
