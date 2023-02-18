<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApplyNotFoundException;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\PresentationToApplyRepositoryUsingDoctrine;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\PresentationToApplyRepositoryUsingDoctrine
 */
final class PresentationToApplyRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanRetrieveData(): void
    {
        $fixture = 'motorsport.championship.championshipPresentation.formulaOneWhite';

        self::loadFixture($fixture);

        $repository = new PresentationToApplyRepositoryUsingDoctrine(self::entityManager());

        $presentationToApply = $repository->findData(new ChampionshipPresentationId($this->fixtureId($fixture)));

        self::assertSame('f1.png', $presentationToApply->icon());
        self::assertSame('#fff', $presentationToApply->color());
    }

    /**
     * @throws Exception
     */
    public function testItRaisesExceptionIfNotFound(): void
    {
        $repository = new PresentationToApplyRepositoryUsingDoctrine(self::entityManager());

        self::expectException(PresentationToApplyNotFoundException::class);

        $repository->findData(new ChampionshipPresentationId('21f6e380-7cde-402b-adcb-7ba3283a9fc0'));
    }
}
