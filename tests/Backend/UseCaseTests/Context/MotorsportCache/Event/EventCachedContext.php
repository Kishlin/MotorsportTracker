<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\SyncEventCacheCommand;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsQuery;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsResponse;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventCachedContext extends MotorsportTrackerContext
{
    private ?ViewCachedEventsResponse $response = null;
    private ?Throwable $thrownException         = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventCachedRepositorySpy()->clear();
    }

    #[Given('the event cached :event exists')]
    public function theEventCachedExists(string $event): void
    {
        try {
            self::container()->cacheFixtureLoader()->loadFixture(
                "motorsport.event.events.{$this->format($event)}",
            );
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[Given('there are no events cached')]
    public function thereAreNoEventsPlanned(): void
    {
    }

    #[When('a client syncs the events cache')]
    public function aClientSyncsTheEventsCache(): void
    {
        $this->thrownException = null;

        try {
            self::container()->commandBus()->execute(SyncEventCacheCommand::create());
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[When('a client views cached events')]
    public function aClientViewsCachedEvents(): void
    {
        $this->response        = null;
        $this->thrownException = null;

        try {
            $response = self::container()->queryBus()->ask(ViewCachedEventsQuery::create());

            Assert::assertInstanceOf(ViewCachedEventsResponse::class, $response);

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('it successfully synced events')]
    public function itSuccessfullySyncedEvents(): void
    {
        Assert::assertNull($this->thrownException);
    }

    #[Then('it has :count events cached')]
    public function itHasXEventsCached(int $count): void
    {
        Assert::assertCount($count, self::container()->eventCachedRepositorySpy()->all());
    }

    #[Then('it has an event cached for :championship :year :event')]
    public function itHasAnEventCachedFor(string $championship, int $year, string $event): void
    {
        Assert::assertNotNull(
            self::container()->eventCachedRepositorySpy()->find(
                StringHelper::slugify($championship),
                $year,
                StringHelper::slugify($event),
            )
        );
    }

    #[Then('it has no cached events')]
    public function itHasNoCachedEvents(): void
    {
        $this->itHasXEventsCached(0);
    }

    #[Then('it views an empty list of events')]
    public function itViewsAnEmptyListOfEvents(): void
    {
        Assert::assertNotNull($this->response);

        Assert::assertEmpty($this->response->events()->toArray());
    }

    #[Then('it views a list of :count events')]
    public function itViewsAListOfEvents(int $count): void
    {
        Assert::assertNotNull($this->response);

        Assert::assertCount($count, $this->response->events()->toArray());
    }

    #[Then('there is a view for event :championship :year :event')]
    public function thereIsAViewForEvent(string $championship, int $year, string $event): void
    {
        Assert::assertNotNull($this->response);

        foreach ($this->response->events()->toArray() as $eventView) {
            if (StringHelper::slugify($championship) !== $eventView['championship']) {
                continue;
            }

            if ($year !== $eventView['year']) {
                continue;
            }

            if (StringHelper::slugify($event) !== $eventView['event']) {
                continue;
            }

            Assert::assertTrue(true);

            return;
        }

        Assert::fail("There is no view for event {$championship} {$year} {$event}.");
    }
}
