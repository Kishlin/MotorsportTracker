<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\SyncEventCacheCommand;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventCachedContext extends MotorsportTrackerContext
{
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventCachedRepositorySpy()->clear();
    }

    #[Given('the event cached exists')]
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
}
