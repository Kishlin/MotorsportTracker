<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\TeamCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class TeamCreationContext extends MotorsportTrackerContext
{
    private const TEAM_NAME  = 'Red Bull Racing';
    private const TEAM_IMAGE = 'https://cdn.motorsporttracker.com/teams/redbullracing.png';

    private const TEAM_NAME_ALT  = 'Mercedes F1';
    private const TEAM_IMAGE_ALT = 'https://cdn.motorsporttracker.com/teams/merecedesf1.png';

    private ?TeamId $teamId             = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->teamRepositorySpy()->clear();
    }

    /**
     * @Given /^a team exists for the country$/
     */
    public function aTeamExists(): void
    {
        self::container()->teamRepositorySpy()->add(Team::instance(
            new TeamId(self::TEAM_ID),
            new TeamName(self::TEAM_NAME),
            new TeamImage(self::TEAM_IMAGE),
            new TeamCountryId(self::COUNTRY_ID),
        ));
    }

    /**
     * @Given /^another team exists for the country$/
     */
    public function anotherTeamExists(): void
    {
        self::container()->teamRepositorySpy()->add(Team::instance(
            new TeamId(self::TEAM_ID_ALT),
            new TeamName(self::TEAM_NAME_ALT),
            new TeamImage(self::TEAM_IMAGE_ALT),
            new TeamCountryId(self::COUNTRY_ID),
        ));
    }

    /**
     * @When /^a client creates a new team for the country$/
     * @When /^a client creates a team with same name$/
     * @When /^a client creates a team for a missing country$/
     */
    public function aClientCreatesATeam(): void
    {
        $this->teamId          = null;
        $this->thrownException = null;

        try {
            /** @var TeamId $teamId */
            $teamId = self::container()->commandBus()->execute(
                CreateTeamCommand::fromScalars(self::COUNTRY_ID, self::TEAM_IMAGE, self::TEAM_NAME),
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
