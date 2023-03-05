<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipIfNotExists\SaveChampionshipRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship
 */
final class SaveChampionshipRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = Championship::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Formula One'),
            new StringValueObject('formula1'),
        );

        $repository = new SaveChampionshipRepositoryUsingDoctrine(self::entityManager());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }
}
