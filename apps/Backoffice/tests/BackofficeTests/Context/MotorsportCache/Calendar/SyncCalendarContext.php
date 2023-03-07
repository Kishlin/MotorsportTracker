<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportCache\Calendar;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Apps\Backoffice\MotorsportCache\Calendar\SyncCalendarEventsCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class SyncCalendarContext extends BackofficeContext
{
    private const COUNT_EVENTS_QUERY = 'SELECT count(id) FROM calendar_events WHERE slug = :slug;';

    private ?int $commandStatus = null;

    #[Given('there are no events planned')]
    public function thereAreNoEventsPlanned(): void
    {
    }

    #[When('a client synchronizes calendar events for :championship :year')]
    public function aClientSynchronizesCalendarEvents(string $championship, int $year): void
    {
        $this->commandStatus = null;

        $commandTester = new CommandTester(
            self::application()->find(SyncCalendarEventsCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['championship' => $championship, 'year' => $year]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('it cached :count calendar events')]
    public function thereAreCalendarEventCached(int $count): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        Assert::assertSame($count, self::cacheDatabase()->fetchOne('SELECT count(id) FROM calendar_events;'));
    }

    #[Then('there is a calendar event cached for :event')]
    public function thereIsACalendarEventCached(string $event): void
    {
        $eventId   = self::coreDatabase()->fixtureId("motorsport.event.event.{$this->format($event)}");
        $eventSlug = self::coreDatabase()->fetchOne('SELECT slug FROM events WHERE id = :eventId;', ['eventId' => $eventId]);

        $calendarEvents = self::cacheDatabase()->fetchOne(self::COUNT_EVENTS_QUERY, ['slug' => $eventSlug]);
        Assert::assertSame(1, $calendarEvents);
    }
}
