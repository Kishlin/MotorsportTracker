<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Calendar;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\SyncSeasonEventsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SeasonEventsCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $seasonEventsId = null;
    private ?Throwable $thrownException      = null;

    public function clearGatewaySpies(): void
    {
        self::container()->seasonEventsRepositorySpy()->clear();
    }

    #[Given('the season events for :seasonEvents exist')]
    public function theSeasonEventsExist(string $seasonEvents): void
    {
        try {
            self::container()->cacheFixtureLoader()->loadFixture("motorsport.calendar.seasonEvents.{$this->format($seasonEvents)}");
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[When('a client syncs the season events for :championship :year')]
    public function aClientSyncsTheSeasonEvents(string $championship, int $year): void
    {
        $this->seasonEventsId  = null;
        $this->thrownException = null;

        try {
            $seasonEventsId = self::container()->commandBus()->execute(
                SyncSeasonEventsCommand::fromScalars($championship, $year),
            );

            assert(null === $seasonEventsId || $seasonEventsId instanceof UuidValueObject);

            $this->seasonEventsId = $seasonEventsId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the season events are cached for :championship :year')]
    public function theSeasonEventsAreCached(string $championship, int $year): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->seasonEventsId);

        Assert::assertSame(
            $this->seasonEventsId->value(),
            self::container()
                ->seasonEventsRepositorySpy()
                ->find(new StringValueObject($championship), new StrictlyPositiveIntValueObject($year))
                ?->id()
                ->value(),
        );
    }

    #[Then('it cached the event of slug :slug for :championship :year')]
    public function itCachedTheEventOfSlug(string $slug, string $championship, int $year): void
    {
        $cached = self::container()->seasonEventsRepositorySpy()->find(
            new StringValueObject($championship),
            new StrictlyPositiveIntValueObject($year),
        );

        Assert::assertNotNull($cached);

        Assert::assertArrayHasKey($slug, $cached->events()->data());
    }

    #[Then('it did not cache the event of slug :slug for :championship :year')]
    public function itDidNotCacheTheEventOfSlug(string $slug, string $championship, int $year): void
    {
        $cached = self::container()->seasonEventsRepositorySpy()->find(
            new StringValueObject($championship),
            new StrictlyPositiveIntValueObject($year),
        );

        Assert::assertNotNull($cached);

        Assert::assertArrayNotHasKey($slug, $cached->events()->data());
    }

    #[Then('it cached no events for :championship :year')]
    public function itCachedNoEvents(string $championship, int $year): void
    {
        $cached = self::container()->seasonEventsRepositorySpy()->find(
            new StringValueObject($championship),
            new StrictlyPositiveIntValueObject($year),
        );

        Assert::assertNotNull($cached);

        Assert::assertEmpty($cached->events()->data());
    }
}
