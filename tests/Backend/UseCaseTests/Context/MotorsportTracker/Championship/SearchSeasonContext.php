<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonResponse;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SeasonNotFoundException;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchSeasonContext extends MotorsportTrackerContext
{
    private ?SearchSeasonResponse $response = null;
    private ?Throwable $thrownException     = null;

    public function clearGatewaySpies(): void
    {
    }

    #[When('a client searches seasons with keyword :championship and year :year')]
    public function itSearchesASeason(string $championship, int $year): void
    {
        $this->response        = null;
        $this->thrownException = null;

        try {
            /** @var SearchSeasonResponse $response */
            $response = self::container()->queryBus()->ask(
                SearchSeasonQuery::fromScalars($championship, $year),
            );

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the id of the season :season is returned')]
    public function theIdOfTheSeasonIsReturned(string $season): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertNull($this->thrownException);

        Assert::assertSame(
            $this->fixtureId("motorsport.championship.season.{$this->format($season)}"),
            $this->response->seasonId()->value(),
        );
    }

    #[Then('it does not receive any season id')]
    public function itDoesNotReceiveAnySeason(): void
    {
        Assert::assertNull($this->response);
        Assert::assertInstanceOf(SeasonNotFoundException::class, $this->thrownException);
    }
}
