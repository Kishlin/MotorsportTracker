<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Standing;

use Behat\Gherkin\Node\TableNode;
use Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class ViewStandingsForSeasonContext extends MotorsportTrackerContext
{
    private ?Response $response = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @Given /^no driver standing exist yet$/
     */
    public function noDriverStandingExists(): void
    {
    }

    /**
     * @Given the standing for :standing exists
     *
     * @throws Exception
     */
    public function theStandingExists(string $standing): void
    {
        self::container()->fixtureLoader()->loadFixture("motorsport.standing.driverStanding.{$this->format($standing)}");
    }

    /**
     * @When a client views the driver standings for season :season
     */
    public function aClientViewsTheDriverStandingsForSeason(string $season): void
    {
        $this->response = self::container()->queryBus()->ask(
            ViewDriverStandingsForSeasonQuery::fromScalars(
                self::fixtureId("motorsport.championship.season.{$this->format($season)}")
            ),
        );
    }

    /**
     * @Then /^it views the driver standings to be$/
     */
    public function itViewsTheDriverStandingsToBe(TableNode $expectedStandings): void
    {
        assert($this->response instanceof ViewDriverStandingsForSeasonResponse);

        /** @var array{driver: string, points: string, eventIndex: int}[] $expectedStandings */
        $expected = $expectedStandings;

        $actual = $this->response->driverStandingsView()->toArray();

        foreach ($expected as $expectedStanding) {
            $driverId = self::fixtureId("motorsport.driver.driver.{$this->format($expectedStanding['driver'])}");

            Assert::assertSame(
                (float) ($expectedStanding['points']),
                $actual[$expectedStanding['eventIndex']][$driverId],
            );
        }
    }

    /**
     * @Then /^it receives an empty driver standings response$/
     */
    public function itReceivesAnEmptyDriverStandingsResponse(): void
    {
        assert($this->response instanceof ViewDriverStandingsForSeasonResponse);

        Assert::assertEmpty($this->response->driverStandingsView()->toArray());
    }
}
