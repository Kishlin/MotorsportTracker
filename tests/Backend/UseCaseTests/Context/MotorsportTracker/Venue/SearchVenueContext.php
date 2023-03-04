<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Venue;

use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueQuery;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueResponse;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\VenueNotFoundException;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchVenueContext extends MotorsportTrackerContext
{
    private ?SearchVenueResponse $response = null;
    private ?Throwable $thrownException    = null;

    public function clearGatewaySpies(): void
    {
    }

    #[When('a client searches for the venue :slug')]
    public function aClientSearchesForTheVenue(string $slug): void
    {
        $this->response        = null;
        $this->thrownException = null;

        try {
            $response = self::container()->queryBus()->ask(
                SearchVenueQuery::fromScalar($slug),
            );

            assert($response instanceof SearchVenueResponse);

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the id of the venue :name is returned')]
    public function theIdOfTheVenueIsReturned(string $name): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->response);

        Assert::assertSame(
            $this->fixtureId("motorsport.venue.venue.{$this->format($name)}"),
            $this->response->venueId()->value(),
        );
    }

    #[Then('it does not receive any venue id')]
    public function itDoesNotReceiveAnyVenueId(): void
    {
        Assert::assertNull($this->response);
        Assert::assertInstanceOf(VenueNotFoundException::class, $this->thrownException);
    }
}
