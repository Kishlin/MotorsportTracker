<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\DriverNotFoundException;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQuery;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchDriverContext extends MotorsportTrackerContext
{
    private ?SearchDriverResponse $response = null;
    private ?Throwable $thrownException     = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @When a client searches for the driver :name
     */
    public function aClientSearchesForTheDriver(string $name): void
    {
        $this->response        = null;
        $this->thrownException = null;

        try {
            $response = self::container()->queryBus()->ask(
                SearchDriverQuery::fromScalars($name),
            );

            assert($response instanceof SearchDriverResponse);

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the id of the driver :name is returned
     */
    public function theIdOfTheDriverIsReturned(string $name): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->response);

        Assert::assertSame(
            $this->fixtureId("motorsport.driver.driver.{$this->format($name)}"),
            $this->response->driverId()->value(),
        );
    }

    /**
     * @Then /^it does not receive any driver id$/
     */
    public function itDoesNotReceiveAnyDriverId(): void
    {
        Assert::assertNull($this->response);
        Assert::assertInstanceOf(DriverNotFoundException::class, $this->thrownException);
    }
}
