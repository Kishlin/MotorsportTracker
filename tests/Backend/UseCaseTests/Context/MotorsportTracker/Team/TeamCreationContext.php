<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Team;

use Exception;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\TeamCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class TeamCreationContext extends MotorsportTrackerContext
{
    private ?TeamId $teamId             = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->teamRepositorySpy()->clear();
    }

    /**
     * @Given the :teamName team exists
     * @Given the team :teamName exists
     *
     * @throws Exception
     */
    public function theTeamExists(string $teamName): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.team.team.{$this->format($teamName)}");
    }

    /**
     * @When a client creates the team :team for the country :country
     * @When /^a client creates a team with the same name$/
     * @When /^a client creates a team for a missing country$/
     */
    public function aClientCreatesTheTeam(string $team = 'Red Bull Racing', string $country = 'Austria'): void
    {
        $this->teamId          = null;
        $this->thrownException = null;

        try {
            /** @var TeamId $teamId */
            $teamId = self::container()->commandBus()->execute(
                CreateTeamCommand::fromScalars(
                    $this->fixtureId("country.country.{$this->format($country)}"),
                    'https://example.com/team.webp',
                    $team,
                ),
            );

            $this->teamId = $teamId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the team is saved$/
     */
    public function theTeamIsSaved(): void
    {
        Assert::assertNotNull($this->teamId);
        Assert::assertTrue(self::container()->teamRepositorySpy()->has($this->teamId));
    }

    /**
     * @Then /^the team creation is declined$/
     */
    public function theTeamCreationIsDeclined(): void
    {
        Assert::assertNull($this->teamId);
        Assert::assertInstanceOf(TeamCreationFailureException::class, $this->thrownException);
    }
}
