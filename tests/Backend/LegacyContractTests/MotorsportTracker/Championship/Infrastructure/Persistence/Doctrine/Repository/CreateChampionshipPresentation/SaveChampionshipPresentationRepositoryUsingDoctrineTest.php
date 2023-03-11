<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipPresentation;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipPresentation\SaveChampionshipPresentationRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateChampionshipPresentation\SaveChampionshipPresentationRepositoryUsingDoctrine
 */
final class SaveChampionshipPresentationRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveAChampionshipPresentation(): void
    {
        $championshipPresentation = ChampionshipPresentation::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::uuid()),
            new StringValueObject('f1.png'),
            new StringValueObject('#fff'),
            new DateTimeValueObject(new DateTimeImmutable()),
        );

        $repository = new SaveChampionshipPresentationRepositoryUsingDoctrine(self::entityManager());

        $repository->save($championshipPresentation);

        self::assertAggregateRootWasSaved($championshipPresentation);
    }
}
