<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\SeasonCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SeasonCreationContext extends MotorsportTrackerContext
{
    private const SEASON_YEAR = 1993;

    private ?SeasonId $seasonId         = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->seasonRepositorySpy()->clear();
    }

    /**
     * @Given /^a season exists for the championship$/
     */
    public function aSeasonExistsForTheChampionship(): void
    {
        self::container()->seasonRepositorySpy()->add(Season::instance(
            new SeasonId(self::SEASON_ID),
            new SeasonYear(self::SEASON_YEAR),
            new SeasonChampionshipId(self::CHAMPIONSHIP_ID),
        ));
    }

    /**
     * @When /^a client creates a season for the same year$/
     * @When /^a client creates a season for the championship$/
     */
    public function aClientCreatesASeasonForTheChampionship(): void
    {
        $this->seasonId        = null;
        $this->thrownException = null;

        try {
            /** @var SeasonId $seasonId */
            $seasonId = self::container()->commandBus()->execute(
                CreateSeasonCommand::fromScalars(self::CHAMPIONSHIP_ID, self::SEASON_YEAR),
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
