<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SaveChampionshipGatewayUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship
 */
final class SaveChampionshipGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = Championship::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('Formula One'),
            new StringValueObject('formula1'),
        );

        $repository = new SaveChampionshipGatewayUsingDoctrine(self::entityManager());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }
}
