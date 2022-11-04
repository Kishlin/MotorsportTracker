<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Championship;

use Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddSeasonCommand;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateSeasonContext extends BackofficeContext
{
    private ?int $year = null;

    private ?int $commandStatus = null;

    /**
     * @Given the season :name exists
     */
    public function theSeasonExists(string $name): void
    {
        self::database()->loadFixture("motorsport.championship.season.{$this->format($name)}");
    }

    /**
     * @When a client creates the season :year for the championship :championship
     * @When /^a client creates a season for the same championship and year$/
     */
    public function aClientCreatesTheSeasonForTheChampionship(int $year = 2022, string $championship = ''): void
    {
        $this->commandStatus = null;

        $this->year = $year;

        $commandTester = new CommandTester(
            self::application()->find(AddSeasonCommand::NAME),
        );

        $commandTester->execute(['year' => $year]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the season is saved$/
     */
    public function theSeasonIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        /** @var array<array{id: string, year: int}> $seasonsData */
        $seasonsData = self::database()->fetchAllAssociative('SELECT * FROM seasons;');

        Assert::assertCount(1, $seasonsData);
        Assert::assertSame($this->year, $seasonsData[0]['year']);
    }

    /**
     * @Then /^the season creation is declined$/
     */
    public function theSeasonCreationIsDeclined(): void
    {
        Assert::assertSame(Command::FAILURE, $this->commandStatus);

        Assert::assertCount(1, self::database()->fetchAllAssociative('SELECT * FROM seasons;') ?? []);
    }
}
