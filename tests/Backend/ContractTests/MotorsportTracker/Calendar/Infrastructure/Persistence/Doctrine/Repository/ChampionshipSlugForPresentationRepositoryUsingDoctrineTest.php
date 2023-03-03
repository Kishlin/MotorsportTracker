<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\ChampionshipSlugForPresentationRepositoryUsingDoctrine;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\ChampionshipSlugForPresentationRepositoryUsingDoctrine
 */
final class ChampionshipSlugForPresentationRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanFindASlug(): void
    {
        self::loadFixtures(
            'motorsport.championship.championship.formulaOne',
            'motorsport.championship.championshipPresentation.formulaOneWhite',
        );

        $repository = new ChampionshipSlugForPresentationRepositoryUsingDoctrine(self::entityManager());

        $presentationId = self::fixtureId('motorsport.championship.championshipPresentation.formulaOneWhite');

        self::assertSame('formula1', $repository->findChampionshipSlugForPresentationId(
            new ChampionshipPresentationId($presentationId),
        ));
    }

    public function testItFindsTheCorrectSlug(): void
    {
        self::loadFixtures(
            'motorsport.championship.championship.formulaOne',
            'motorsport.championship.championship.motoGp',
            'motorsport.championship.championshipPresentation.formulaOneWhite',
            'motorsport.championship.championshipPresentation.motoGpBlack',
        );

        $repository = new ChampionshipSlugForPresentationRepositoryUsingDoctrine(self::entityManager());

        $presentationId = self::fixtureId('motorsport.championship.championshipPresentation.formulaOneWhite');

        self::assertSame('formula1', $repository->findChampionshipSlugForPresentationId(
            new ChampionshipPresentationId($presentationId),
        ));
    }
}
