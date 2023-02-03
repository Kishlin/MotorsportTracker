<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationColor;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationIcon;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SaveChampionshipPresentationGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SaveChampionshipPresentationGatewayUsingDoctrine
 */
final class SaveChampionshipPresentationGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAChampionshipPresentation(): void
    {
        $championshipPresentation = ChampionshipPresentation::instance(
            new ChampionshipPresentationId(self::uuid()),
            new ChampionshipPresentationChampionshipId(self::uuid()),
            new ChampionshipPresentationIcon('f1.png'),
            new ChampionshipPresentationColor('#fff'),
            new ChampionshipPresentationCreatedOn(new DateTimeImmutable()),
        );

        $repository = new SaveChampionshipPresentationGatewayUsingDoctrine(self::entityManager());

        $repository->save($championshipPresentation);

        self::assertAggregateRootWasSaved($championshipPresentation);
    }
}
