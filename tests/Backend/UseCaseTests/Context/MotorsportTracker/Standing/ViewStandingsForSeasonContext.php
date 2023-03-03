<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Standing;

use Behat\Gherkin\Node\TableNode;
use Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonResponse;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonResponse;
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
     * @Given /^no team standing exist yet$/
     */
    public function noDriverStandingExists(): void
    {
    }

    /**
     * @Given the :class standing for :standing exists
     *
     * @throws Exception
     */
    public function theStandingExists(string $class, string $standing): void
    {
        self::container()->coreFixtureLoader()->loadFixture(
            "motorsport.standing.{$class}Standing.{$this->format($standing)}",
        );
    }

    /**
     * @When a client views the driver standings for season :season
     *
     * @throws Exception
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
     * @When a client views the team standings for season :season
     *
     * @throws Exception
     */
    public function aClientViewsTheTeamStandingsForSeason(string $season): void
    {
        $this->response = self::container()->queryBus()->ask(
            ViewTeamStandingsForSeasonQuery::fromScalars(
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
     * @Then /^it views the team standings to be$/
     */
    public function itViewsTheTealStandingsToBe(TableNode $expectedStandings): void
    {
        assert($this->response instanceof ViewTeamStandingsForSeasonResponse);

        /** @var array{team: string, points: string, eventIndex: int}[] $expectedStandings */
        $expected = $expectedStandings;

        $actual = $this->response->teamStandingsView()->toArray();

        foreach ($expected as $expectedStanding) {
            $driverId = self::fixtureId("motorsport.team.team.{$this->format($expectedStanding['team'])}");

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

    /**
     * @Then /^it receives an empty team standings response$/
     */
    public function itReceivesAnEmptyTeamStandingsResponse(): void
    {
        assert($this->response instanceof ViewTeamStandingsForSeasonResponse);

        Assert::assertEmpty($this->response->teamStandingsView()->toArray());
    }
}
