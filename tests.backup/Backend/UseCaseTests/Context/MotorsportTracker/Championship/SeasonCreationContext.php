<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\CreateSeasonIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SeasonCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $seasonId  = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->seasonRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the season :name exists')]
    public function theSeasonExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.championship.season.{$this->format($name)}");
    }

    #[When('a client creates a season for the same championship and year')]
    #[When('a client creates the season :year for the championship :championship')]
    public function aClientCreatesTheSeason(int $year = 2022, string $championship = 'Formula One'): void
    {
        $this->seasonId        = null;
        $this->thrownException = null;

        try {
            $championshipId = $this->fixtureId("motorsport.championship.series.{$this->format($championship)}");

            /** @var UuidValueObject $seasonId */
            $seasonId = self::container()->commandBus()->execute(
                CreateSeasonIfNotExistsCommand::fromScalars($championshipId, $year, null),
            );

            $this->seasonId = $seasonId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the season is saved')]
    #[Then('the id of the season :season is returned')]
    public function theSeasonIsSaved(): void
    {
        Assert::assertNotNull($this->seasonId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->seasonRepositorySpy()->has($this->seasonId));
    }
}
