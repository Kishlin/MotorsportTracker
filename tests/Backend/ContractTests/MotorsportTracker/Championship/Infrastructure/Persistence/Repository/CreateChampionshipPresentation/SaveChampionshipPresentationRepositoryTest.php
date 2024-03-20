<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipPresentation;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipPresentation\SaveChampionshipPresentationRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipPresentation\SaveChampionshipPresentationRepository
 */
final class SaveChampionshipPresentationRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAChampionshipPresentation(): void
    {
        self::loadFixture('series.formula_one');

        $championshipPresentation = ChampionshipPresentation::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('series.formula_one')),
            new StringValueObject('f1.png'),
            new StringValueObject('#fff'),
            new DateTimeValueObject(new DateTimeImmutable()),
        );

        $repository = new SaveChampionshipPresentationRepository(self::connection());

        $repository->save($championshipPresentation);

        self::assertAggregateRootWasSaved($championshipPresentation);
    }
}
