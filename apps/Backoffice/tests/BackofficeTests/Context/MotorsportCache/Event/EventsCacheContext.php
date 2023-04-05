<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportCache\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Apps\Backoffice\MotorsportCache\Event\SyncEventsCacheCommandUsingSymfony;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Throwable;

final class EventsCacheContext extends BackofficeContext
{
    private const COUNT_QUERY = 'SELECT count(id) FROM event_cached;';

    private const FIND_QUERY = 'SELECT count(id) FROM event_cached WHERE championship = :c AND year = :y AND event = :e;';

    private ?int $commandStatus = null;

    #[Given('the event cached exists')]
    public function theEventCachedExists(string $event): void
    {
        try {
            self::cacheDatabase()->loadFixture("motorsport.event.events.{$this->format($event)}");
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[When('a client syncs the events cache')]
    public function aClientSyncsTheEventsCache(): void
    {
        $this->commandStatus = null;

        $commandTester = new CommandTester(
            self::application()->find(SyncEventsCacheCommandUsingSymfony::NAME),
        );

        $commandTester->execute([]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('it successfully synced events')]
    public function itSuccessfullySyncedEvents(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);
    }

    #[Then('it has :count events cached')]
    public function itHasXEventsCached(int $count): void
    {
        Assert::assertSame($count, self::cacheDatabase()->fetchOne(self::COUNT_QUERY));
    }

    #[Then('it has an event cached for :championship :year :event')]
    public function itHasAnEventCachedFor(string $championship, int $year, string $event): void
    {
        $params = [
            'c' => StringHelper::slugify($championship),
            'e' => StringHelper::slugify($event),
            'y' => $year,
        ];

        Assert::assertSame(1, self::cacheDatabase()->fetchOne(self::FIND_QUERY, $params));
    }

    #[Then('it has no cached events')]
    public function itHasNoCachedEvents(): void
    {
        $this->itHasXEventsCached(0);
    }
}
