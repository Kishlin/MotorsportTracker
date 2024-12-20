<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Championship;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use DateInterval;
use Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddChampionshipPresentationCommand;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Throwable;

final class CreateChampionshipPresentationContext extends BackofficeContext
{
    private const QUERY = <<<'SQL'
SELECT icon, color
FROM championship_presentation
WHERE championship = :championship
ORDER BY created_on DESC;
SQL;

    private ?string $icon  = null;
    private ?string $color = null;

    private ?int $commandStatus = null;

    /**
     * @throws Throwable
     */
    #[Given('the championship :championship exists')]
    public function theChampionshipExists(string $championship): void
    {
        self::database()->loadFixture("motorsport.championship.series.{$this->format($championship)}");
    }

    /**
     * @throws Throwable
     */
    #[Given('the championship presentation :championshipPresentation exists')]
    public function theChampionshipPresentationExists(string $championshipPresentation): void
    {
        self::database()->loadFixture(
            "motorsport.championship.championshipPresentation.{$this->format($championshipPresentation)}",
        );
    }

    #[When('a client creates a championship presentation for :championship with icon :icon and color :color')]
    public function aClientCreatesAChampionshipPresentation(string $championship, string $icon, string $color): void
    {
        $this->commandStatus = null;

        $this->icon  = $icon;
        $this->color = $color;

        $commandTester = new CommandTester(
            self::application()->find(AddChampionshipPresentationCommand::NAME),
        );

        // This guaranties time uniqueness in database, when multiple presentations are created in one test
        self::application()->advanceInTime(new DateInterval('PT1S'));

        $commandTester->execute(['championship' => $championship, 'icon' => $icon, 'color' => $color]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('the championship presentation is saved')]
    public function theChampionshipPresentationIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        /** @var array<array{id: string, championshipId: string, icon: string, color: string}> $championshipPresentationData */
        $championshipPresentationData = self::database()->fetchAllAssociative(
            'SELECT * FROM championship_presentation ORDER BY created_on DESC LIMIT 1;'
        );

        Assert::assertCount(1, $championshipPresentationData);
        Assert::assertSame($this->icon, $championshipPresentationData[0]['icon']);
        Assert::assertSame($this->color, $championshipPresentationData[0]['color']);
    }

    #[Then('the latest championship presentation for :championship has icon :icon and color :color')]
    public function theLatestChampionshipPresentationIs(string $championship, string $icon, string $color): void
    {
        $championshipId = self::database()->fixtureId("motorsport.championship.series.{$this->format($championship)}");

        /** @var null|array{id: string, championshipId: string, icon: string, color: string} $championshipPresentationData */
        $championshipPresentationData = self::database()->fetchAssociative(self::QUERY, ['championship' => $championshipId]);

        Assert::assertNotNull($championshipPresentationData);

        Assert::assertSame($icon, $championshipPresentationData['icon']);
        Assert::assertSame($color, $championshipPresentationData['color']);
    }
}
