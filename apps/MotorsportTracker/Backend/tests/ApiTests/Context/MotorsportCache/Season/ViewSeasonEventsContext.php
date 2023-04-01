<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportCache\Season;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ViewSeasonEventsContext extends BackendApiContext
{
    private Response $response;

    #[Given('the season events for :seasonEvents exist')]
    public function theSeasonEventsExist(string $seasonEvents): void
    {
        try {
            self::cacheDatabase()->loadFixture("motorsport.calendar.seasonEvents.{$this->format($seasonEvents)}");
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[When('a client views the season events for :championship :year')]
    public function aClientViewsTheSeasonEvents(string $championship, int $year): void
    {
        $slug = StringHelper::slugify($championship);

        try {
            $this->response = self::handle(Request::create("/api/v1/season/{$slug}/{$year}/events"));
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }
    }

    #[Then('it views season events for :championship :year')]
    public function itViewsSeasonEvents(): void
    {
        Assert::assertSame(Response::HTTP_OK, $this->response->getStatusCode());
    }

    #[Then('it views a season event of slug :slug')]
    public function itViewsAnEventOfSlug(string $slug): void
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        /** @var array<string, array<string, int|string>> $responseData */
        $responseData = json_decode($responseContent, true);

        Assert::assertNotFalse($responseData);
        Assert::assertArrayHasKey($slug, $responseData);
    }

    #[Then('it gets a missing season events error')]
    public function itGetsAMissingSeasonEventsError(): void
    {
        Assert::assertSame(Response::HTTP_NOT_FOUND, $this->response->getStatusCode());
    }
}
