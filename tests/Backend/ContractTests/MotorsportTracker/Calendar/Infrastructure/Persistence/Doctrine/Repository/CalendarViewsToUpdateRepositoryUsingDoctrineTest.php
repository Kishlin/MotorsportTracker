<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\CalendarViewsToUpdateRepositoryUsingDoctrine;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\CalendarViewsToUpdateRepositoryUsingDoctrine
 */
final class CalendarViewsToUpdateRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanFindAllViews(): void
    {
        $australianGPFixture = 'motorsport.calendar.calendarEventStepView.australianGrandPrix2022RaceWhite';
        $dutchGPFixture      = 'motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite';

        self::loadFixtures(
            'motorsport.championship.championshipPresentation.formulaOneWhite',
            $australianGPFixture,
            $dutchGPFixture,
        );

        $repository = new CalendarViewsToUpdateRepositoryUsingDoctrine(self::entityManager());

        $presentationId = new ChampionshipPresentationId(
            self::fixtureId('motorsport.championship.championshipPresentation.formulaOneWhite'),
        );

        self::assertEqualsCanonicalizing(
            [self::fixtureId($australianGPFixture), self::fixtureId($dutchGPFixture)],
            $repository->findForPresentation($presentationId)->idList(),
        );
    }

    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyArrayWhenNoViewMatch(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite',
            'motorsport.championship.championshipPresentation.formulaOneWhite',
            'motorsport.championship.championshipPresentation.motoGpBlack',
        );

        $repository = new CalendarViewsToUpdateRepositoryUsingDoctrine(self::entityManager());

        self::assertEmpty(
            $repository
                ->findForPresentation(
                    new ChampionshipPresentationId(
                        self::fixtureId('motorsport.championship.championshipPresentation.motoGpBlack'),
                    ),
                )
                ->idList(),
        );

        self::assertEmpty(
            $repository
                ->findForPresentation(
                    new ChampionshipPresentationId('7ba54db6-224f-4533-a44b-42bb6b2e4d65'),
                )
                ->idList(),
        );
    }
}
