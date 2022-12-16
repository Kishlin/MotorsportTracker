<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportTracker\Standing;

use Behat\Gherkin\Node\TableNode;
use Exception;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class StandingContext extends BackendApiContext
{
    private Response $response;

    /**
     * @Given the :class standing for :standing exists
     */
    public function theStandingForExists(string $class, string $standing): void
    {
        self::database()->loadFixture(
            "motorsport.standing.{$class}Standing.{$this->format($standing)}",
        );
    }

    /**
     * @Given /^no team standing exist yet$/
     * @Given /^no driver standing exist yet$/
     */
    public function noTeamStandingExistYet(): void
    {
    }

    /**
     * @When a client views the team standings for season :season
     *
     * @throws Exception
     */
    public function aClientViewsTheTeamStandingsForSeason(string $season): void
    {
        $championship = $this->format(substr($season, 0, strlen($season) - 4));
        $year         = substr($season, -4, 4);

        $this->response = self::handle(Request::create("/api/v1/standings/teams/{$championship}/{$year}", 'GET'));
    }

    /**
     * @When a client views the driver standings for season :season
     *
     * @throws Exception
     */
    public function aClientViewsTheDriverStandingsForSeason(string $season): void
    {
        $championship = $this->format(substr($season, 0, strlen($season) - 4));
        $year         = substr($season, -4, 4);

        $this->response = self::handle(Request::create("/api/v1/standings/drivers/{$championship}/{$year}", 'GET'));
    }

    /**
     * @Then /^it views the team standings to be$/
     */
    public function itViewsTheTeamStandingsToBe(TableNode $expectedStandings): void
    {
        /** @var array{team: string, points: string, eventIndex: int}[] $expectedStandings */
        $expected = $expectedStandings;

        $content = $this->response->getContent();
        Assert::assertNotFalse($content);

        $actual = json_decode($content, true);
        Assert::assertIsArray($actual);

        foreach ($expected as $expectedStanding) {
            $teamId = self::fixtureId("motorsport.team.team.{$this->format($expectedStanding['team'])}");

            Assert::assertSame(
                (float) ($expectedStanding['points']),
                (float) $actual[$expectedStanding['eventIndex']][$teamId],
            );
        }
    }

    /**
     * @Then /^it views the driver standings to be$/
     */
    public function itViewsTheDriverStandingsToBe(TableNode $expectedStandings): void
    {
        /** @var array{driver: string, points: string, eventIndex: int}[] $expectedStandings */
        $expected = $expectedStandings;

        $content = $this->response->getContent();
        Assert::assertNotFalse($content);

        $actual = json_decode($content, true);
        Assert::assertIsArray($actual);

        foreach ($expected as $expectedStanding) {
            $driverId = self::fixtureId("motorsport.driver.driver.{$this->format($expectedStanding['driver'])}");

            Assert::assertSame(
                (float) ($expectedStanding['points']),
                (float) $actual[$expectedStanding['eventIndex']][$driverId],
            );
        }
    }

    /**
     * @Then /^it receives an empty team standings response$/
     * @Then /^it receives an empty driver standings response$/
     */
    public function itReceivesAnEmptyTeamStandingsResponse(): void
    {
        $content = $this->response->getContent();
        Assert::assertNotFalse($content);

        $actual = json_decode($content, true);
        Assert::assertIsArray($actual);

        Assert::assertEmpty($actual);
    }
}
