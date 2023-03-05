<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Venue;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\CreateVenueIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class VenueCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $venueId   = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->venueRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the venue :name exists')]
    public function theVenueAlreadyExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.venue.venue.{$this->format($name)}");
    }

    #[When('a client creates the venue :name for the :country if it does not exist with slug :slug')]
    public function aClientCreatesTheVenueIfItDoesNotExist(string $name, string $country, string $slug): void
    {
        $this->venueId         = null;
        $this->thrownException = null;

        try {
            /** @var UuidValueObject $venueId */
            $venueId = self::container()->commandBus()->execute(
                CreateVenueIfNotExistsCommand::fromScalars(
                    $name,
                    $slug,
                    $this->fixtureId("country.country.{$this->format($country)}"),
                ),
            );

            $this->venueId = $venueId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the venue is saved')]
    public function theVenueIsSaved(): void
    {
        Assert::assertNotNull($this->venueId);
        Assert::assertTrue(self::container()->venueRepositorySpy()->has($this->venueId));
    }

    #[Then('the id of the existing venue :name is returned')]
    #[Then('the id of the freshly created venue :name is returned')]
    public function theIdOfTheVenueIsReturned(string $name): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->venueId);

        Assert::assertTrue(self::container()->venueRepositorySpy()->has($this->venueId));
    }
}
