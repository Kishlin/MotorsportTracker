<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Calendar;

use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\SyncCalendarEventsCommand;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SyncCalendarContext extends MotorsportTrackerContext
{
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->calendarEventRepositorySpy()->clear();
    }

    #[When('a client synchronizes calendar events for :championship :year')]
    public function aClientSynchronizesCalendarEvents(string $championship, int $year): void
    {
        $this->thrownException = null;

        try {
            self::container()->commandBus()->execute(
                SyncCalendarEventsCommand::fromScalars($championship, $year),
            );
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('it cached :count calendar events')]
    public function thereAreCalendarEventCached(int $count): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertCount($count, self::container()->calendarEventRepositorySpy()->all());
    }

    #[Then('there is a calendar event cached for :session')]
    public function thereIsACalendarEventCached(string $session): void
    {
        $sessionId = new UuidValueObject(self::fixtureId("motorsport.event.event.{$this->format($session)}"));

        $total = count(
            array_filter(
                self::container()->calendarEventRepositorySpy()->all(),
                static function (CalendarEvent $calendarEvent) use ($sessionId) {
                    return $calendarEvent->reference()->equals($sessionId);
                }
            )
        );

        Assert::assertSame(1, $total);
    }
}
