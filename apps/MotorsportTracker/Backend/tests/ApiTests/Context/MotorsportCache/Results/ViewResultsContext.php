<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportCache\Results;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ViewResultsContext extends BackendApiContext
{
    private const EVENT_FOR_RESULT = 'SELECT event from event_results_by_race WHERE id = :id';

    private Response $response;

    public function clearGatewaySpies(): void
    {
    }

    #[Given('the results for the :result exist')]
    public function theRaceResultExists(string $result): void
    {
        self::cacheDatabase()->loadFixture("motorsport.result.eventResultsByRace.{$this->format($result)}");
    }

    /**
     * @throws Exception
     */
    #[When('a client views results for the :result')]
    public function aClientViewsResultsForEvent(string $result): void
    {
        try {
            $eventIdForResult = $this->eventIdForResult($result);
        } catch (\Throwable) {
        }

        $eventId = $eventIdForResult ?? '25883b9a-4b76-4894-b467-15174583cbd3';

        $this->response = self::handle(Request::create("/api/v1/results/{$eventId}"));
    }

    #[Then('the results for the :result are returned')]
    public function itViewsTheResultsForThe(string $result): void
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        $actual = json_decode($responseContent, true);
        Assert::assertIsArray($actual);

        Assert::assertSame($this->eventIdForResult($result), $actual['event']);
    }

    #[Then('it views no results for any race')]
    public function itViewsNoResultsForAnyRace(): void
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        $actual = json_decode($responseContent, true);
        Assert::assertIsArray($actual);
        Assert::assertArrayHasKey('resultsByRace', $actual);

        Assert::assertNotFalse($responseContent);
        Assert::assertSame([], $actual['resultsByRace']);
    }

    private function eventIdForResult(string $result): string
    {
        $resultId = $this->fixtureId("motorsport.result.eventResultsByRace.{$this->format($result)}");

        $eventId = self::cacheDatabase()->fetchOne(self::EVENT_FOR_RESULT, ['id' => $resultId]);
        assert(is_string($eventId));

        return $eventId;
    }
}
