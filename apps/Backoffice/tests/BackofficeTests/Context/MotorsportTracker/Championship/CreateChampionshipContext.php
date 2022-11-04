<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Championship;

use Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddChampionshipCommand;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateChampionshipContext extends BackofficeContext
{
    private ?string $name = null;
    private ?string $slug = null;

    private ?int $commandStatus = null;

    /**
     * @Given the championship :name exists
     */
    public function theChampionshipExists(string $name): void
    {
        self::database()->loadFixture("motorsport.championship.championship.{$this->format($name)}");
    }

    /**
     * @When a client creates the championship :name with slug :slug
     * @When a client creates a championship with the same name
     */
    public function aClientCreatesTheChampionshipWithSlug(string $name = 'Formula 1', string $slug = 'f1'): void
    {
        $this->name = $name;
        $this->slug = $slug;

        $this->commandStatus = null;

        $commandTester = new CommandTester(
            self::application()->find(AddChampionshipCommand::NAME),
        );

        $commandTester->execute(['name' => $name, 'slug' => $slug]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the championship is saved$/
     */
    public function theChampionshipIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        /** @var array<array{id: string, name: string, slug: string}> $championshipData */
        $championshipData = self::database()->fetchAllAssociative('SELECT * FROM championships;');

        Assert::assertCount(1, $championshipData);

        Assert::assertSame($this->name, $championshipData[0]['name']);
        Assert::assertSame($this->slug, $championshipData[0]['slug']);
    }

    /**
     * @Then the championship creation is declined
     */
    public function theChampionshipCreationIsDeclined(): void
    {
        Assert::assertSame(Command::FAILURE, $this->commandStatus);

        Assert::assertCount(1, self::database()->fetchAllAssociative('SELECT id FROM championships;') ?? []);
    }
}
