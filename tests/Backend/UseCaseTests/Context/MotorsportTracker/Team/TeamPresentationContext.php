<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Team;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\CreateTeamPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class TeamPresentationContext extends MotorsportTrackerContext
{
    private ?TeamPresentationId $teamPresentationId = null;
    private ?Throwable $thrownException             = null;

    public function clearGatewaySpies(): void
    {
        self::container()->teamPresentationRepositorySpy()->clear();
    }

    #[Given('the team presentation :teamPresentation exists')]
    public function theTeamPresentationExists(string $teamPresentation): void
    {
        self::container()->fixtureLoader()->loadFixture(
            "motorsport.team.teamPresentation.{$this->format($teamPresentation)}",
        );
    }

    #[When('a client creates a team presentation for :team with name :name and image :image')]
    public function aClientCreatesATeamPresentationForWithNameAndImage(string $team, string $name, string $image): void
    {
        $this->teamPresentationId = null;
        $this->thrownException    = null;

        $teamId = $this->fixtureId("motorsport.team.team.{$this->format($team)}");

        try {
            /** @var TeamPresentationId $teamPresentationId */
            $teamPresentationId = self::container()->commandBus()->execute(
                CreateTeamPresentationCommand::fromScalars($teamId, $name, $image),
            );

            $this->teamPresentationId = $teamPresentationId;
        } catch (Throwable $exception) {
            $this->thrownException = $exception;
        }
    }

    #[Then('the team presentation is saved')]
    public function theTeamPresentationIsSaved(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->teamPresentationId);

        Assert::assertTrue(
            self::container()->teamPresentationRepositorySpy()->has($this->teamPresentationId),
        );
    }

    #[Then('the latest team presentation for :team has name :name and image :image')]
    public function theLatestTeamPresentationForHasNameAndImage(string $team, string $name, string $image): void
    {
        $teamId = $this->fixtureId("motorsport.team.team.{$this->format($team)}");

        $teamPresentation = self::container()->teamPresentationRepositorySpy()->latest(new TeamId($teamId));

        Assert::assertNotNull($teamPresentation);

        Assert::assertSame($name, $teamPresentation->name()->value());
        Assert::assertSame($image, $teamPresentation->image()->value());
    }
}
