<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Calendar;

use Behat\Step\Given;
use Behat\Step\Then;
use Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class CalendarViewContext extends MotorsportTrackerContext
{
    public function clearGatewaySpies(): void
    {
        self::container()->calendarEventStepViewRepositorySpy()->clear();
    }

    /**
     * @Throws Exception
     */
    #[Given('the calendar event view :name exists')]
    public function theCalendarEventViewExists(string $name): void
    {
        self::container()->fixtureLoader()->loadFixture("motorsport.calendar.calendarEventStepView.{$this->format($name)}");
    }

    #[Then('the event step calendar view is created for :name :type on :dateTime for :slug')]
    public function theEventStepCalendarViewIsCreated(string $name, string $type, string $dateTime, string $slug): void
    {
        $matchingViews = array_filter(
            self::container()->calendarEventStepViewRepositorySpy()->all(),
            static function (CalendarEventStepView $view) use ($name, $type, $dateTime, $slug) {
                return $dateTime === $view->dateTime()->value()->format('Y-m-d H:i:s')
                    && $slug     === $view->championshipSlug()->value()
                    && $name     === $view->name()->value()
                    && $type     === $view->type()->value()
                ;
            },
        );

        Assert::assertCount(1, $matchingViews);
    }

    #[Then('the event step calendar for :slug :name :type has the :color color')]
    public function theEventStepCalendarForEventHasColor(string $slug, string $name, string $type, string $color): void
    {
        $matchingViews = array_filter(
            self::container()->calendarEventStepViewRepositorySpy()->all(),
            static function (CalendarEventStepView $view) use ($name, $type, $color, $slug) {
                return $slug  === $view->championshipSlug()->value()
                    && $color === $view->color()->value()
                    && $name  === $view->name()->value()
                    && $type  === $view->type()->value()
                ;
            },
        );

        Assert::assertCount(1, $matchingViews);
    }

    #[Then('the event step calendar view was not created')]
    public function theEventStepCalendarViewWasNotCreated(): void
    {
        Assert::assertEmpty(self::container()->calendarEventStepViewRepositorySpy()->all());
    }
}
