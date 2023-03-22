<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists\SaveRetirementRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists\SaveRetirementRepository
 */
final class SaveRetirementRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveARetirement(): void
    {
        self::loadFixture('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $retirement = Retirement::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race')),
            new StringValueObject('reason'),
            new StringValueObject('type'),
            new BoolValueObject(true),
            new PositiveIntValueObject(35),
        );

        $repository = new SaveRetirementRepository(self::connection());

        $repository->save($retirement);

        self::assertAggregateRootWasSaved($retirement);
    }
}
