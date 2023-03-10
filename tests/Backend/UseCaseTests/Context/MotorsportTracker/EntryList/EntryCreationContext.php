<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\EntryList;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists\CreateEntryIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EntryCreationContext extends MotorsportTrackerContext
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
        self::container()->coreFixtureLoader()->loadFixture("motorsport.entrylist.entry.{$this->format($name)}");
    }

    #[When('a client creates the entry for event :event driver :driver with number :number')]
    #[When('a client creates an entry for the same event, driver, and number')]
    public function aClientCreatesAEntry(
        string $event = 'Dutch Grand Prix 2022',
        string $driver = 'Max Verstappen',
        string $number = '1',
    ): void {
        $this->entryId         = null;
        $this->thrownException = null;

        try {
            $eventId  = $this->fixtureId("motorsport.event.event.{$this->format($event)}");
            $driverId = $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}");

            /** @var UuidValueObject $entryId */
            $entryId = self::container()->commandBus()->execute(
                CreateEntryIfNotExistsCommand::fromScalars($eventId, $driverId, null, 'chassis', 'engine', null, null, $number),
            );

            $this->entryId = $entryId;
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

    #[Then('the id of the new entry for car number :number is returned')]
    public function theIdOfTheEntryIsReturned(string $number): void
    {
        Assert::assertNotNull($this->entryId);
        Assert::assertNull($this->thrownException);

        Assert::assertSame($number, self::container()->entryRepositorySpy()->safeGet($this->entryId)->carNumber()->value());
    }

    #[Then('the id of the existing entry for car number :number is returned')]
    public function theIdOfTheExistingEntryIsReturned(string $number): void
    {
        $this->theIdOfTheEntryIsReturned($number);
    }
}
