<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ViewChampionshipsContext extends MotorsportTrackerContext
{
    private ?ViewAllChampionshipsResponse $response = null;

    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @Given /^there are no championships stored$/
     */
    public function thereAreNoChampionshipsStored(): void
    {
    }

    /**
     * @When /^a client views all championships$/
     */
    public function aClientViewsAllChampionships(): void
    {
        $this->response        = null;
        $this->thrownException = null;

        try {
            /** @var ViewAllChampionshipsResponse $response */
            $response = self::container()->queryBus()->ask(new ViewAllChampionshipsQuery());

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^a response with no championships is returned$/
     */
    public function anEmptyResponseIsReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertNull($this->thrownException);
        Assert::assertEmpty($this->response->championships());
    }

    /**
     * @Then a response will all :count championships is returned
     */
    public function aResponseWithAllChampionshipsIsReturned(string $count): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertNull($this->thrownException);

        $championships = $this->response->championships();

        Assert::assertCount((int) $count, $championships);
    }
}
