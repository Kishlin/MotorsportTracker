<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Venue;

use Exception;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class VenueCreationContext extends MotorsportTrackerContext
{
    private ?VenueId $venueId           = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->venueRepositorySpy()->clear();
    }

    /**
     * @Given the venue :name exists
     *
     * @throws Exception
     */
    public function theVenueAlreadyExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.venue.venue.{$this->format($name)}");
    }

    /**
     * @When a client creates the venue :name for the :country
     */
    public function aClientCreatesTheVenue(string $name, string $country): void
    {
        $this->venueId         = null;
        $this->thrownException = null;

        try {
            /** @var VenueId $venueId */
            $venueId = self::container()->commandBus()->execute(
                CreateVenueCommand::fromScalars(
                    $name,
                    $this->fixtureId("country.country.{$this->format($country)}"),
                ),
            );

            $this->venueId = $venueId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the venue is saved$/
     */
    public function theVenueIsSaved(): void
    {
        Assert::assertNotNull($this->venueId);
        Assert::assertTrue(self::container()->venueRepositorySpy()->has($this->venueId));
    }

    /**
     * @Then /^the venue creation is rejected$/
     */
    public function theVenueCreationIsRejected(): void
    {
        Assert::assertNotNull($this->thrownException);
        Assert::assertInstanceOf(VenueCreationFailureException::class, $this->thrownException);
    }
}
