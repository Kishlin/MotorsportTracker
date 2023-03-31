<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Result;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceQuery;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ViewResultsContext extends MotorsportTrackerContext
{
    private ?Throwable $thrownException = null;
    private ?Response $response         = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @throws Exception
     */
    #[Given('the results for the :result exist')]
    public function theRaceResultExists(string $result): void
    {
        self::container()
            ->cacheFixtureLoader()
            ->loadFixture("motorsport.result.eventResultsByRace.{$this->format($result)}")
        ;
    }

    #[When('a client views results for the :result')]
    public function aClientViewsResultsForEvent(string $result): void
    {
        $this->thrownException = null;
        $this->response        = null;

        try {
            $resultId = new UuidValueObject($this->fixtureId("motorsport.result.eventResultsByRace.{$this->format($result)}"));
            $eventId  = self::container()->eventResultsByRaceRepositorySpy()->get($resultId)?->event()->value();
        } catch (Throwable) {
        }

        try {
            $response = self::container()->queryBus()->ask(ViewEventResultsByRaceQuery::fromScalars($eventId ?? 'missing'));

            assert($response instanceof Response);

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the results for the :result are returned')]
    public function itViewsTheResultsForThe(string $result): void
    {
        Assert::assertNull($this->thrownException);

        assert($this->response instanceof ViewEventResultsByRaceResponse);

        $resultId = new UuidValueObject($this->fixtureId("motorsport.result.eventResultsByRace.{$this->format($result)}"));

        Assert::assertSame(
            self::container()->eventResultsByRaceRepositorySpy()->safeGet($resultId)->event()->value(),
            $this->response->view()->toArray()['event'],
        );

        Assert::assertIsArray($this->response->view()->toArray());
    }

    #[Then('it views no results for any race')]
    public function itViewsNoResultsForAnyRace(): void
    {
        Assert::assertNull($this->thrownException);

        assert($this->response instanceof ViewEventResultsByRaceResponse);

        Assert::assertIsArray($this->response->view()->toArray());
    }
}
