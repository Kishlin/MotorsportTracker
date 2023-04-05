<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportCache\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ViewCachedEventsContext extends BackendApiContext
{
    private Response $response;

    #[Given('there are no events cached')]
    public function thereAreNoEventsPlanned(): void
    {
    }

    #[Given('the event cached :event exists')]
    public function theEventCachedExists(string $event): void
    {
        try {
            self::cacheDatabase()->loadFixture("motorsport.event.events.{$this->format($event)}");
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[When('a client views cached events')]
    public function aClientViewsCachedEvents(): void
    {
        try {
            $this->response = self::handle(Request::create('/api/v1/events/'));
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[Then('it views an empty list of events')]
    public function itViewsAnEmptyListOfEvents(): void
    {
        Assert::assertSame(Response::HTTP_OK, $this->response->getStatusCode());

        Assert::assertEmpty($this->decodedResponse());
    }

    #[Then('it views a list of :count events')]
    public function itViewsAListOfEvents(int $count): void
    {
        Assert::assertSame(Response::HTTP_OK, $this->response->getStatusCode());

        Assert::assertCount($count, $this->decodedResponse());
    }

    #[Then('there is a view for event :championship :year :event')]
    public function thereIsAViewForEvent(string $championship, int $year, string $event): void
    {
        foreach ($this->decodedResponse() as $eventView) {
            if (StringHelper::slugify($championship) !== $eventView['championship']) {
                continue;
            }

            if ($year !== $eventView['year']) {
                continue;
            }

            if (StringHelper::slugify($event) !== $eventView['event']) {
                continue;
            }

            Assert::assertTrue(true);

            return;
        }

        Assert::fail("There is no view for event {$championship} {$year} {$event}.");
    }

    /**
     * @return array<array{championship: string, year: int, event: string}>
     */
    private function decodedResponse(): array
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        /** @var array<array{championship: string, year: int, event: string}>|false $responseData */
        $responseData = json_decode($responseContent, true);

        Assert::assertIsArray($responseData);

        return $responseData;
    }
}
