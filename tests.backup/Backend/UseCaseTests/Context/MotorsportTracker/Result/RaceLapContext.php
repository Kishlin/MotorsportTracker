<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Result;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\CreateRaceLapIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class RaceLapContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $raceLapId = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->raceLapRepositorySpy()->clear();
    }

    /**
     * @throws Throwable
     */
    #[Given('the race lap :name exists')]
    public function theRaceLapExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.result.raceLap.{$this->format($name)}");
    }

    #[When('a client creates the race lap for the entry :entry at lap :lap')]
    public function aClientCreatesARaceLap(string $entry = 'maxVerstappenForRedBullRacingAtDutchGP2022Race', int $lap = 10): void
    {
        $this->raceLapId       = null;
        $this->thrownException = null;

        try {
            $entryId = $this->fixtureId("motorsport.result.entry.{$this->format($entry)}");

            /** @var UuidValueObject $raceLapId */
            $raceLapId = self::container()->commandBus()->execute(
                CreateRaceLapIfNotExistsCommand::fromScalars(
                    $entryId,
                    $lap,
                    9,
                    false,
                    99745,
                    10957,
                    0,
                    602,
                    0,
                    [['type' => 'S', 'wear' => 'u', 'laps' => 6]],
                ),
            );

            $this->raceLapId = $raceLapId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the race lap is saved')]
    #[Then('the race lap is not duplicated')]
    public function theRaceLapIsSaved(): void
    {
        Assert::assertCount(1, self::container()->raceLapRepositorySpy()->all());

        Assert::assertNotNull($this->raceLapId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->raceLapRepositorySpy()->has($this->raceLapId));
    }

    #[Then('the id of the race lap for the entry :entry at lap :lap is returned')]
    public function theIdOfTheRaceLapIsReturned(string $entry, int $lap): void
    {
        Assert::assertNotNull($this->raceLapId);
        Assert::assertNull($this->thrownException);

        $raceLapId = self::container()->raceLapRepositorySpy()->findForEntryAndLap(
            new UuidValueObject(self::fixtureId("motorsport.result.entry.{$this->format($entry)}")),
            new PositiveIntValueObject($lap),
        );

        Assert::assertNotNull($raceLapId);
        Assert::assertSame($raceLapId, $this->raceLapId);
    }
}
