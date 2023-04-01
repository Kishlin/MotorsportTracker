<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportCache\Calendar;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Apps\Backoffice\MotorsportCache\Calendar\SyncSeasonEventsCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Throwable;

final class SeasonEventsContext extends BackofficeContext
{
    private const FIND_QUERY = 'SELECT * FROM season_events WHERE championship = :c AND year = :y;';

    private ?int $commandStatus = null;

    #[Given('the season events for :seasonEvents exist')]
    public function theSeasonEventsExist(string $seasonEvents): void
    {
        try {
            self::cacheDatabase()->loadFixture("motorsport.calendar.seasonEvents.{$this->format($seasonEvents)}");
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[When('a client syncs the season events for :championship :year')]
    public function aClientSyncsTheSeasonEvents(string $championship, int $year): void
    {
        $this->commandStatus = null;

        $commandTester = new CommandTester(
            self::application()->find(SyncSeasonEventsCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['championship' => $championship, 'year' => $year]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('the season events are cached for :championship :year')]
    public function theSeasonEventsAreCached(string $championship, int $year): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $cached = $this->fetchCachedSeasonEvents($championship, $year);

        Assert::assertIsArray($cached);
        Assert::assertCount(1, $cached);
    }

    #[Then('it cached the event of slug :slug for :championship :year')]
    public function itCachedTheEventOfSlug(string $slug, string $championship, int $year): void
    {
        $cached = $this->fetchCachedSeasonEvents($championship, $year);

        Assert::assertIsArray($cached);
        Assert::assertCount(1, $cached);
        Assert::assertIsString($cached[0]['events']);

        /** @var array<string, array{id: string, name: string, slug: string, index: int}>|false $events */
        $events = json_decode($cached[0]['events'], true);
        if (false === $events) {
            Assert::fail('Failed to decode events.');
        }

        Assert::assertArrayHasKey($slug, $events);
    }

    #[Then('it did not cache the event of slug :slug for :championship :year')]
    public function itDidNotCacheTheEventOfSlug(string $slug, string $championship, int $year): void
    {
        $cached = $this->fetchCachedSeasonEvents($championship, $year);

        Assert::assertIsArray($cached);
        Assert::assertCount(1, $cached);
        Assert::assertIsString($cached[0]['events']);

        /** @var array<string, array{id: string, name: string, slug: string, index: int}>|false $events */
        $events = json_decode($cached[0]['events'], true);
        if (false === $events) {
            Assert::fail('Failed to decode events.');
        }

        Assert::assertArrayNotHasKey($slug, $events);
    }

    #[Then('it cached no events for :championship :year')]
    public function itCachedNoEvents(string $championship, int $year): void
    {
        $cached = $this->fetchCachedSeasonEvents($championship, $year);

        Assert::assertIsArray($cached);
        Assert::assertCount(1, $cached);
        Assert::assertIsString($cached[0]['events']);

        /** @var array<string, array{id: string, name: string, slug: string, index: int}>|false $events */
        $events = json_decode($cached[0]['events'], true);
        if (false === $events) {
            Assert::fail('Failed to decode events.');
        }

        Assert::assertEmpty($events);
    }

    /**
     * @return null|array<array<string, null|bool|float|int|string>>
     */
    private function fetchCachedSeasonEvents(string $championship, int $year): ?array
    {
        return self::cacheDatabase()->fetchAllAssociative(self::FIND_QUERY, ['c' => $championship, 'y' => $year]);
    }
}
