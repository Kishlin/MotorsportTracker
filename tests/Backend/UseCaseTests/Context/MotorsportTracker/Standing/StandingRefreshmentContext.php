<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Standing;

use Behat\Gherkin\Node\TableNode;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class StandingRefreshmentContext extends MotorsportTrackerContext
{
    public function clearGatewaySpies(): void
    {
        self::container()->driverStandingRepositorySpy()->clear();
        self::container()->teamStandingRepositorySpy()->clear();
    }

    /**
     * @Then /^the driver's standings are now$/
     */
    public function theDriversStandingsAreNow(TableNode $standings): void
    {
        $driverStandings = self::container()->driverStandingRepositorySpy()->all();

        Assert::assertCount(count($standings->getRows()) - 1, $driverStandings);

        /** @var array<array{driver: string, event: string, points:float}> $standingsTable */
        $standingsTable = $standings;

        foreach ($standingsTable as $expected) {
            $driverStanding = array_shift($driverStandings);
            assert(null !== $driverStanding);

            Assert::assertEquals(
                self::fixtureId("motorsport.driver.driver.{$this->format($expected['driver'])}"),
                $driverStanding->driverId()->value(),
            );

            Assert::assertEquals(
                self::fixtureId("motorsport.event.event.{$this->format($expected['event'])}"),
                $driverStanding->eventId()->value(),
            );

            Assert::assertEquals($expected['points'], $driverStanding->points()->value());
        }
    }

    /**
     * @Then /^the team's standings are now$/
     */
    public function theTeamsStandingsAreNow(TableNode $standings): void
    {
        $teamStandings = self::container()->teamStandingRepositorySpy()->all();

        Assert::assertCount(count($standings->getRows()) - 1, $teamStandings);

        /** @var array<array{team: string, event: string, points:float}> $standingsTable */
        $standingsTable = $standings;

        foreach ($standingsTable as $expected) {
            $teamStanding = array_shift($teamStandings);
            assert(null !== $teamStanding);

            Assert::assertEquals(
                self::fixtureId("motorsport.team.team.{$this->format($expected['team'])}"),
                $teamStanding->teamId()->value(),
            );

            Assert::assertEquals(
                self::fixtureId("motorsport.event.event.{$this->format($expected['event'])}"),
                $teamStanding->eventId()->value(),
            );

            Assert::assertEquals($expected['points'], $teamStanding->points()->value());
        }
    }
}
