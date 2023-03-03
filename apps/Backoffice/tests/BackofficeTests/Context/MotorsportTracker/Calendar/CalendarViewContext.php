<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Calendar;

use Behat\Step\Given;
use Behat\Step\Then;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;

final class CalendarViewContext extends BackofficeContext
{
    #[Given('the calendar event view :name exists')]
    public function theCalendarEventViewExists(string $name): void
    {
        self::cacheDatabase()->loadFixture("motorsport.calendar.calendarEventStepView.{$this->format($name)}");
    }

    #[Then('the event step calendar view is created for :name :type on :dateTime for :slug')]
    public function theEventStepCalendarViewIsCreated(string $name, string $type, string $dateTime, string $slug): void
    {
        $query = 'SELECT date_time, championship_slug, name, type FROM calendar_event_step_views;';

        /** @var array<array{championship_slug: string, name: string, type: string, date_time: string}> $views */
        $views = self::cacheDatabase()->fetchAllAssociative($query);

        $matchingViews = array_filter(
            $views,
            static function (array $view) use ($name, $type, $dateTime, $slug) {
                return $dateTime === $view['date_time']
                    && $slug     === $view['championship_slug']
                    && $name     === $view['name']
                    && $type     === $view['type']
                    ;
            },
        );

        Assert::assertCount(1, $matchingViews);
    }

    #[Then('the event step calendar for :slug :name :type has the :color color')]
    public function theEventStepCalendarForEventHasColor(string $slug, string $name, string $type, string $color): void
    {
        $query = 'SELECT color, championship_slug, name, type FROM calendar_event_step_views;';

        /** @var array<array{championship_slug: string, name: string, type: string, color: string}> $views */
        $views = self::cacheDatabase()->fetchAllAssociative($query);

        $matchingViews = array_filter(
            $views,
            static function (array $view) use ($name, $type, $color, $slug) {
                return $color === $view['color']
                    && $slug  === $view['championship_slug']
                    && $name  === $view['name']
                    && $type  === $view['type']
                    ;
            },
        );

        Assert::assertCount(1, $matchingViews);
    }
}
