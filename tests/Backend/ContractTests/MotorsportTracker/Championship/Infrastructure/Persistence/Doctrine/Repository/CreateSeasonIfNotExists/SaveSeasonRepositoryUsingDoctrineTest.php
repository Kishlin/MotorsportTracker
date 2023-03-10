<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateSeasonIfNotExists\SaveSeasonRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateSeasonIfNotExists\SaveSeasonRepositoryUsingDoctrine
 */
final class SaveSeasonRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveASeason(): void
    {
        self::loadFixture('motorsport.championship.championship.formulaOne');

        $season = Season::instance(
            new UuidValueObject(self::uuid()),
            new StrictlyPositiveIntValueObject(2022),
            new UuidValueObject(self::fixtureId('motorsport.championship.championship.formulaOne')),
            new NullableUuidValueObject(self::uuid()),
        );

        $repository = new SaveSeasonRepositoryUsingDoctrine(self::entityManager());

        $repository->save($season);

        self::assertAggregateRootWasSaved($season);
    }
}
