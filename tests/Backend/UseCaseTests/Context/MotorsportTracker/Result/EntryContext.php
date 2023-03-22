<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Result;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\CreateEntryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\EntryCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberQuery;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberResponse;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Exception\EntryNotFoundException;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EntryContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $entryId   = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->entryRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the entry :name exists')]
    public function theEntryExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.result.entry.{$this->format($name)}");
    }

    #[When('a client creates the entry of :driverName for :team at :session with number :carNumber')]
    #[When('a client creates the entry of the same driver for :team at :session with number :carNumber')]
    #[When('a client creates the entry of :driverName for :team at :session with the same number')]
    #[When('a client creates the entry with the same driver team session and number')]
    public function aClientCreatesAnEntry(
        string $driverName = 'Max Verstappen',
        string $team = 'Red Bull Racing',
        string $session = 'dutchGrandPrix2022Race',
        int $carNumber = 33,
    ): void {
        $this->entryId         = null;
        $this->thrownException = null;

        try {
            $sessionId = $this->fixtureId("motorsport.event.eventSession.{$this->format($session)}");
            $teamId    = $this->fixtureId("motorsport.team.team.{$this->format($team)}");

            /** @var UuidValueObject $entryId */
            $entryId = self::container()->commandBus()->execute(
                CreateEntryIfNotExistsCommand::fromScalars($sessionId, $driverName, $teamId, $carNumber),
            );

            $this->entryId = $entryId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[When('a client searches for the entry at :session with number :number')]
    public function aClientSearchesForAnEntry(string $session, int $carNumber = 33): void
    {
        $this->entryId         = null;
        $this->thrownException = null;

        try {
            $sessionId = $this->fixtureId("motorsport.event.eventSession.{$this->format($session)}");

            /** @var FindEntryForSessionAndNumberResponse $response */
            $response = self::container()->queryBus()->ask(
                FindEntryForSessionAndNumberQuery::fromScalars($sessionId, $carNumber),
            );

            $this->entryId = $response->id();
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the entry is saved')]
    #[Then('the entry is not duplicated')]
    public function theEntryIsSaved(): void
    {
        Assert::assertCount(1, self::container()->entryRepositorySpy()->all());

        Assert::assertNotNull($this->entryId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->entryRepositorySpy()->has($this->entryId));
    }

    #[Then('the entry creation is refused')]
    public function theEntryCreationIsRefused(): void
    {
        Assert::assertNull($this->entryId);
        Assert::assertNotNull($this->thrownException);

        Assert::assertInstanceOf(EntryCreationFailureException::class, $this->thrownException);
    }

    #[Then('the id of the entry of :driver for :team at :session with number :carNumber is returned')]
    public function theIdOfTheEntryIsReturned(string $session, string $driver, string $team, int $carNumber): void
    {
        Assert::assertNotNull($this->entryId);
        Assert::assertNull($this->thrownException);

        $entry = self::container()->entryRepositorySpy()->safeGet($this->entryId);

        Assert::assertSame(
            self::fixtureId("motorsport.event.eventSession.{$this->format($session)}"),
            $entry->session()->value(),
        );

        Assert::assertSame(
            self::fixtureId("motorsport.driver.driver.{$this->format($driver)}"),
            $entry->driver()->value(),
        );

        Assert::assertSame(
            self::fixtureId("motorsport.team.team.{$this->format($team)}"),
            $entry->team()->value(),
        );

        Assert::assertSame($carNumber, $entry->carNumber()->value());
    }

    #[Then('it does not find the entry')]
    public function itDoesNotFindTheEntry(): void
    {
        Assert::assertNull($this->entryId);
        Assert::assertNotNull($this->thrownException);

        Assert::assertInstanceOf(EntryNotFoundException::class, $this->thrownException);
    }
}
