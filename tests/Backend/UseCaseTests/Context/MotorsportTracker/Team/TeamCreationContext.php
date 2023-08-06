<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Team;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\CreateTeamIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class TeamCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $teamId    = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->teamRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the :teamName team exists')]
    public function theTeamExists(string $teamName): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.team.team.{$this->format($teamName)}");
    }

    #[When('a client creates a team with name :name and color :color and ref :ref')]
    #[When('a client creates a team with the same name color and ref')]
    public function aClientCreatesTheTeam(
        string $name = 'Red Bull Racing',
        string $color = '#0000c6',
        string $ref = '41be2072-17ab-455f-8522-8b96bc315e47',
    ): void {
        $this->teamId          = null;
        $this->thrownException = null;

        $season = array_keys(self::container()->seasonRepositorySpy()->all())[0];

        try {
            /** @var UuidValueObject $teamId */
            $teamId = self::container()->commandBus()->execute(
                CreateTeamIfNotExistsCommand::fromScalars($season, $name, $color, $ref),
            );

            $this->teamId = $teamId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the team is saved')]
    #[Then('the team is not duplicated')]
    public function theTeamIsSaved(): void
    {
        Assert::assertCount(1, self::container()->teamRepositorySpy()->all());

        Assert::assertNotNull($this->teamId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->teamRepositorySpy()->has($this->teamId));
    }

    #[Then('the id of the team with ref :ref is returned')]
    public function theIdOfTheTeamWithRefIsReturned(string $ref): void
    {
        Assert::assertNotNull($this->teamId);
        Assert::assertSame($ref, self::container()->teamRepositorySpy()->safeGet($this->teamId)->ref()->value());
    }
}
