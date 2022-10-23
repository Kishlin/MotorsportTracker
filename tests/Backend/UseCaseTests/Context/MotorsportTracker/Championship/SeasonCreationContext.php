<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\SeasonCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SeasonCreationContext extends MotorsportTrackerContext
{
    private ?SeasonId $seasonId         = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->seasonRepositorySpy()->clear();
    }

    /**
     * @Given the season :name exists
     *
     * @throws Exception
     */
    public function theSeasonExists(string $name): void
    {
        self::container()->fixtureLoader()->loadFixture("motorsport.championship.season.{$this->format($name)}");
    }

    /**
     * @When a client creates the season :year for the championship :championship
     * @When /^a client creates a season for the same championship and year$/
     */
    public function aClientCreatesTheSeason(int $year = 2022, string $championship = 'Formula One'): void
    {
        $this->seasonId        = null;
        $this->thrownException = null;

        try {
            $championshipId = $this->fixtureId("motorsport.championship.championship.{$this->format($championship)}");

            /** @var SeasonId $seasonId */
            $seasonId = self::container()->commandBus()->execute(
                CreateSeasonCommand::fromScalars($championshipId, $year),
            );

            $this->seasonId = $seasonId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the season is saved$/
     */
    public function theSeasonIsSaved(): void
    {
        Assert::assertNotNull($this->seasonId);
        Assert::assertTrue(self::container()->seasonRepositorySpy()->has($this->seasonId));
    }

    /**
     * @Then /^the season creation is declined$/
     */
    public function theSeasonCreationIsDeclined(): void
    {
        Assert::assertNotNull($this->thrownException);
        Assert::assertInstanceOf(SeasonCreationFailureException::class, $this->thrownException);
    }
}
