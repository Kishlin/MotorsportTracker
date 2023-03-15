<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Team;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\CreateTeamPresentationIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class TeamPresentationCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $teamPresentationId = null;
    private ?Throwable $thrownException          = null;

    public function clearGatewaySpies(): void
    {
        self::container()->teamPresentationRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the team presentation :name exists')]
    public function theTeamExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.team.teamPresentation.{$this->format($name)}");
    }

    #[When('a client creates a presentation with name :name and color :color')]
    #[When('a client creates a presentation for the same team and season')]
    public function aClientCreatesTheTeam(string $name = 'Red Bull Racing', string $color = '#0000c6'): void
    {
        $this->teamPresentationId = null;
        $this->thrownException    = null;

        $ref     = array_keys(self::container()->teamRepositorySpy()->all())[0];
        $season  = array_keys(self::container()->seasonRepositorySpy()->all())[0];
        $country = array_keys(self::container()->countryRepositorySpy()->all())[0];

        try {
            /** @var UuidValueObject $teamPresentationId */
            $teamPresentationId = self::container()->commandBus()->execute(
                CreateTeamPresentationIfNotExistsCommand::fromScalars($ref, $season, $country, $name, $color),
            );

            $this->teamPresentationId = $teamPresentationId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the team presentation is saved')]
    #[Then('the team presentation is not duplicated')]
    public function theTeamIsSaved(): void
    {
        Assert::assertCount(1, self::container()->teamPresentationRepositorySpy()->all());

        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->teamPresentationId);
        Assert::assertTrue(self::container()->teamPresentationRepositorySpy()->has($this->teamPresentationId));
    }

    #[Then('the id of the team presentation :team is returned')]
    public function theIdOfTheTeamIsReturned(string $team): void
    {
        Assert::assertNotNull($this->teamPresentationId);
        Assert::assertNull($this->thrownException);

        Assert::assertSame($team, self::container()->teamPresentationRepositorySpy()->safeGet($this->teamPresentationId)->name()->value());
    }
}
