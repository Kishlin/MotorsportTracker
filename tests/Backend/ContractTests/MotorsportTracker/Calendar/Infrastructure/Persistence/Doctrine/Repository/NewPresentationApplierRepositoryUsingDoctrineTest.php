<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\NewPresentationApplierRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\NewPresentationApplierRepositoryUsingDoctrine
 */
final class NewPresentationApplierRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    private const CHECK_QUERY = 'SELECT color, icon FROM calendar_event_step_views;';

    /**
     * @throws Exception
     */
    public function testItUpdatesAllViews(): void
    {
        $newIcon  = 'new-icon.png';
        $newColor = '#f00';

        self::loadFixtures(
            'motorsport.calendar.calendarEventStepView.australianGrandPrix2022RaceWhite',
            'motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite',
            'motorsport.championship.championshipPresentation.formulaOneWhite',
            'motorsport.championship.championshipPresentation.formulaOneRed',
        );

        $repository = new NewPresentationApplierRepositoryUsingDoctrine(self::entityManager());

        $repository->applyDataToViews(
            CalendarViewsToUpdate::fromScalars([
                self::fixtureId('motorsport.calendar.calendarEventStepView.australianGrandPrix2022RaceWhite'),
                self::fixtureId('motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite'),
            ]),
            PresentationToApply::fromScalars($newColor, $newIcon),
        );

        $result = self::entityManager()->getConnection()->executeQuery(self::CHECK_QUERY)->fetchAllAssociative();

        self::assertCount(2, $result);
        self::assertSame(['color' => $newColor, 'icon' => $newIcon], $result[0]);
        self::assertSame(['color' => $newColor, 'icon' => $newIcon], $result[1]);
    }
}
