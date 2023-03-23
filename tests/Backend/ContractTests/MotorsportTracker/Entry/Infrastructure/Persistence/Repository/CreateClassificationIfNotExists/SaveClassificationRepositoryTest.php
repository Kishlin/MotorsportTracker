<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists\SaveClassificationRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists\SaveClassificationRepository
 */
final class SaveClassificationRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAClassification(): void
    {
        self::loadFixture('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $classification = Classification::instance(
            new UuidValueObject('17e9f5aa-836b-4dfc-a613-3d0b9a55e931'),
            new UuidValueObject('61756cdd-deaa-4304-9c3b-5a889e155ce6'),
            new StrictlyPositiveIntValueObject(9),
            new StrictlyPositiveIntValueObject(20),
            new PositiveIntValueObject(57),
            new PositiveFloatValueObject(2.0),
            new PositiveFloatValueObject(5710489.0),
            new StringValueObject('CLA'),
            new PositiveFloatValueObject(194.319),
            new PositiveFloatValueObject(95068.0),
            new PositiveFloatValueObject(73753.0),
            new PositiveFloatValueObject(1106.0),
            new PositiveIntValueObject(0),
            new PositiveIntValueObject(0),
            new PositiveIntValueObject(42),
            new PositiveFloatValueObject(95068.0),
            new BoolValueObject(false),
        );

        $repository = new SaveClassificationRepository(self::connection());

        $repository->save($classification);

        self::assertAggregateRootWasSaved($classification);
    }
}
