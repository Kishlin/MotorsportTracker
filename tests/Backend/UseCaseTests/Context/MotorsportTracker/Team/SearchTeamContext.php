<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamQuery;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamResponse;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\TeamNotFoundException;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchTeamContext extends MotorsportTrackerContext
{
    private ?SearchTeamResponse $searchTeamResponse = null;
    private ?Throwable          $thrownException    = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @When a client searches a team with keyword :keyword
     */
    public function aClientSearchesATeam(string $keyword): void
    {
        $this->searchTeamResponse = null;
        $this->thrownException    = null;

        try {
            /** @var SearchTeamResponse $response */
            $response = self::container()->queryBus()->ask(
                SearchTeamQuery::fromScalars($keyword),
            );

            $this->searchTeamResponse = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the id of the team :team is returned
     */
    public function theIdOfTheTeamIsReturned(string $team): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->searchTeamResponse);

        Assert::assertSame(
            self::fixtureId("motorsport.team.team.{$this->format($team)}"),
            $this->searchTeamResponse->teamId()->value(),
        );
    }

    /**
     * @Then /^it does not receive any team id$/
     */
    public function itDoesNotReceiveAnyTeamId(): void
    {
        Assert::assertNull($this->searchTeamResponse);
        Assert::assertInstanceOf(TeamNotFoundException::class, $this->thrownException);
    }
}
