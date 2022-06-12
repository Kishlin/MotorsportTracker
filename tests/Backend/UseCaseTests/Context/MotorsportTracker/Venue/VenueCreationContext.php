<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class VenueCreationContext extends MotorsportTrackerContext
{
    private const VENUE_NAME = 'Circuit Zandvoort';

    private ?VenueId $venueId           = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->venueRepositorySpy()->clear();
    }

    /**
     * @Given /^a venue exists for the country$/
     */
    public function aVenueExistsForTheCountry(): void
    {
        self::container()->venueRepositorySpy()->add(Venue::instance(
            new VenueId(self::VENUE_ID),
            new VenueName(self::VENUE_NAME),
            new VenueCountryId(self::COUNTRY_ID),
        ));
    }

    /**
     * @When /^a client creates a new venue for the country$/
     * @When /^a client creates a venue for a missing country$/
     */
    public function aClientCreatesANewVenueForTheCountry(): void
    {
        $this->venueId         = null;
        $this->thrownException = null;

        try {
            /** @var VenueId $venueId */
            $venueId = self::container()->commandBus()->execute(CreateVenueCommand::fromScalars(
                self::VENUE_NAME,
                self::COUNTRY_ID,
            ));

            $this->venueId = $venueId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the new venue is saved$/
     */
    public function theNewVenueIsSaved(): void
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
