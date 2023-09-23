<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipIfNotExists\SaveChampionshipRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipIfNotExists\SaveChampionshipRepository
 */
final class SaveChampionshipRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = Series::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Formula One'),
            new NullableStringValueObject('formula1'),
            new StringValueObject('F1'),
            new NullableUuidValueObject(self::uuid()),
        );

        $repository = new SaveChampionshipRepository(self::connection());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }

    public function testItCanSaveAChampionshipWithNullValues(): void
    {
        $championship = Series::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Formula One'),
            new NullableStringValueObject(null),
            new StringValueObject('formula1'),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveChampionshipRepository(self::connection());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }
}
