<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists\SaveClassificationRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableBoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
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
            new PositiveIntValueObject(9),
            new NullableIntValueObject(20),
            new PositiveIntValueObject(57),
            new PositiveFloatValueObject(2.0),
            new PositiveFloatValueObject(5710489.0),
            new NullableStringValueObject('CLA'),
            new PositiveFloatValueObject(194.319),
            new NullableFloatValueObject(95068.0),
            new FloatValueObject(73753.0),
            new FloatValueObject(1106.0),
            new IntValueObject(0),
            new IntValueObject(0),
            new NullableIntValueObject(42),
            new NullableFloatValueObject(95068.0),
            new NullableBoolValueObject(false),
            new NullableFloatValueObject(285.5),
        );

        $repository = new SaveClassificationRepository(self::connection());

        $repository->save($classification);

        self::assertAggregateRootWasSaved($classification);
    }
}
