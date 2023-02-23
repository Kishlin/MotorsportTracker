<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Team;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use DateInterval;
use Kishlin\Apps\Backoffice\MotorsportTracker\Team\Command\CreateTeamPresentationCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class TeamPresentationContext extends BackofficeContext
{
    private const QUERY = <<<'SQL'
SELECT name, image
FROM team_presentations
WHERE team = :team
ORDER BY created_on DESC;
SQL;

    private ?string $name  = null;
    private ?string $image = null;

    private ?int $commandStatus = null;

    #[Given('the team presentation :teamPresentation exists')]
    public function theTeamPresentationExists(string $teamPresentation): void
    {
        self::database()->loadFixture(
            "motorsport.team.teamPresentation.{$this->format($teamPresentation)}",
        );
    }

    #[When('a client creates a team presentation for :team with name :name and image :image')]
    public function aClientCreatesATeamPresentationForWithNameAndImage(string $team, string $name, string $image): void
    {
        $this->commandStatus = null;

        $this->name  = $name;
        $this->image = $image;

        $commandTester = new CommandTester(
            self::application()->find(CreateTeamPresentationCommandUsingSymfony::NAME),
        );

        // This guaranties time uniqueness in database, when multiple presentations are created in one test
        self::application()->advanceInTime(new DateInterval('PT1S'));

        $commandTester->execute(['team' => $this->format($team), 'name' => $name, 'image' => $image]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('the team presentation is saved')]
    public function theTeamPresentationIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        /** @var array<array{id: string, team: string, name: string, image: string}> $teamPresentationData */
        $teamPresentationData = self::database()->fetchAllAssociative(
            'SELECT * FROM team_presentations ORDER BY created_on DESC LIMIT 1;'
        );

        Assert::assertCount(1, $teamPresentationData);
        Assert::assertSame($this->name, $teamPresentationData[0]['name']);
        Assert::assertSame($this->image, $teamPresentationData[0]['image']);
    }

    #[Then('the latest team presentation for :team has name :name and image :image')]
    public function theLatestTeamPresentationForHasNameAndImage(string $team, string $name, string $image): void
    {
        $teamId = self::database()->fixtureId("motorsport.team.team.{$this->format($team)}");

        /** @var null|array{name: string, image: string} $teamPresentationData */
        $teamPresentationData = self::database()->fetchAssociative(self::QUERY, ['team' => $teamId]);

        Assert::assertNotNull($teamPresentationData);

        Assert::assertSame($name, $teamPresentationData['name']);
        Assert::assertSame($image, $teamPresentationData['image']);
    }
}
