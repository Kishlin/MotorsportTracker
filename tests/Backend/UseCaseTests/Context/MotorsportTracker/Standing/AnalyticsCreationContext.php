<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Standing;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists\CreateAnalyticsIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class AnalyticsCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $analyticsId = null;
    private ?Throwable $thrownException   = null;

    public function clearGatewaySpies(): void
    {
        self::container()->analyticsRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the analytics for :name exist')]
    public function theAnalyticsExist(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.standing.analytics.{$this->format($name)}");
    }

    #[When('a client creates the analytics for :driver during :season')]
    #[When('a client creates the analytics for the same driver and season')]
    public function aClientCreatesAnalytics(TableNode $stats, string $driver = 'Max Verstappen', string $season = 'formulaOne2022'): void
    {
        $this->analyticsId     = null;
        $this->thrownException = null;

        /** @var array<int, string> $analytics */
        $analytics = $stats->getColumn(1);

        try {
            $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");
            $driverId = $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}");

            /** @var UuidValueObject $analyticsId */
            $analyticsId = self::container()->commandBus()->execute(
                CreateAnalyticsIfNotExistsCommand::fromScalars(
                    $seasonId,
                    $driverId,
                    (int) $analytics[0],
                    (float) $analytics[1],
                    AnalyticsStatsDTO::fromScalars(
                        (float) $analytics[2],
                        (int) $analytics[3],
                        (int) $analytics[4],
                        (int) $analytics[5],
                        (int) $analytics[6],
                        (int) $analytics[7],
                        (int) $analytics[8],
                        (int) $analytics[9],
                        (int) $analytics[10],
                        (int) $analytics[11],
                        (int) $analytics[12],
                        (int) $analytics[13],
                        (int) $analytics[14],
                        (int) $analytics[15],
                        (int) $analytics[16],
                        (int) $analytics[17],
                        (float) $analytics[18],
                    ),
                ),
            );

            $this->analyticsId = $analyticsId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the analytics are saved')]
    #[Then('the analytics are not duplicated')]
    public function theAnalyticsAreSaved(): void
    {
        Assert::assertCount(1, self::container()->analyticsRepositorySpy()->all());

        Assert::assertNotNull($this->analyticsId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->analyticsRepositorySpy()->has($this->analyticsId));
    }

    #[Then('the id of the analytics for :driver during :season is returned')]
    public function theIdOfTheAnalyticsIsReturned(string $driver, string $season): void
    {
        Assert::assertNotNull($this->analyticsId);
        $analytics = self::container()->analyticsRepositorySpy()->safeGet($this->analyticsId);

        $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");
        $driverId = $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}");

        Assert::assertSame($seasonId, $analytics->season()->value());
        Assert::assertSame($driverId, $analytics->driver()->value());
    }
}
