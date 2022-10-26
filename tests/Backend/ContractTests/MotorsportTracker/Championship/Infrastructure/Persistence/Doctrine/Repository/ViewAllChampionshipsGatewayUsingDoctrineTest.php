<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\ViewAllChampionshipsGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\ViewAllChampionshipsGatewayUsingDoctrine
 */
final class ViewAllChampionshipsGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanViewAllChampionships(): void
    {
        self::loadFixtures(
            'motorsport.championship.championship.formulaOne',
            'motorsport.championship.championship.motoGp',
            'motorsport.championship.championship.wec',
        );

        $repository = new ViewAllChampionshipsGatewayUsingDoctrine(self::entityManager());

        $championships = $repository->viewAllChampionships();

        self::assertCount(3, $championships);
        self::assertSame('Formula 1', $championships[0]->name());
        self::assertSame('Moto GP', $championships[1]->name());
        self::assertSame('FIA World Endurance Championship', $championships[2]->name());
    }
}
