<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\CreateChampionshipPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ChampionshipPresentationCreationContext extends MotorsportTrackerContext
{
    private ?ChampionshipPresentationId $championshipPresentationId = null;
    private ?Throwable $thrownException                             = null;

    public function clearGatewaySpies(): void
    {
        self::container()->championshipPresentationRepositorySpy()->clear();
    }

    /**
     * @throws Throwable
     */
    #[Given('the championship presentation :championshipPresentation exists')]
    public function theChampionshipPresentationExists(string $championshipPresentation): void
    {
        self::container()->coreFixtureLoader()->loadFixture(
            "motorsport.championship.championshipPresentation.{$this->format($championshipPresentation)}",
        );
    }

    #[When('a client creates a championship presentation for :championship with icon :icon and color :color')]
    public function aClientCreatesAChampionshipPresentation(string $championship, string $icon, string $color): void
    {
        $this->championshipPresentationId = null;
        $this->thrownException            = null;

        $championshipId = $this->fixtureId("motorsport.championship.championship.{$this->format($championship)}");

        try {
            /** @var ChampionshipPresentationId $championshipPresentationId */
            $championshipPresentationId = self::container()->commandBus()->execute(
                CreateChampionshipPresentationCommand::fromScalars($championshipId, $icon, $color),
            );

            $this->championshipPresentationId = $championshipPresentationId;
        } catch (Throwable $exception) {
            $this->thrownException = $exception;
        }
    }

    #[Then('the championship presentation is saved')]
    public function theChampionshipPresentationIsSaved(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->championshipPresentationId);

        Assert::assertTrue(
            self::container()->championshipPresentationRepositorySpy()->has($this->championshipPresentationId),
        );
    }

    #[Then('the latest championship presentation for :championship has icon :icon and color :color')]
    public function theLatestChampionshipPresentationIs(string $championship, string $icon, string $color): void
    {
        $championshipId = $this->fixtureId("motorsport.championship.championship.{$this->format($championship)}");

        $championshipPresentation = self::container()->championshipPresentationRepositorySpy()->latest(
            new UuidValueObject($championshipId),
        );

        Assert::assertNotNull($championshipPresentation);

        Assert::assertSame($color, $championshipPresentation->color()->value());
        Assert::assertSame($icon, $championshipPresentation->icon()->value());
    }
}
