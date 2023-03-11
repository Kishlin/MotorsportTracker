<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists\SaveChampionshipRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship
 */
final class SaveChampionshipRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = Championship::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Formula One'),
            new NullableStringValueObject('formula1'),
            new StringValueObject('F1'),
            new NullableUuidValueObject(self::uuid()),
        );

        $repository = new SaveChampionshipRepositoryUsingDoctrine(self::entityManager());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }

    public function testItCanSaveAChampionshipWithNullValues(): void
    {
        $championship = Championship::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Formula One'),
            new NullableStringValueObject(null),
            new StringValueObject('formula1'),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveChampionshipRepositoryUsingDoctrine(self::entityManager());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }
}
