<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportCache\Result;

use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Apps\Backoffice\MotorsportCache\Result\ComputeEventResultsByRaceCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Throwable;

final class ComputeResultContext extends BackofficeContext
{
    private ?int $commandStatus = null;

    #[When('a client computes results for the event :event')]
    public function aClientComputesResultsForEvent(string $event): void
    {
        $this->commandStatus = null;

        $eventId = self::coreDatabase()->fixtureId("motorsport.event.event.{$this->format($event)}");

        $commandTester = new CommandTester(
            self::application()->find(ComputeEventResultsByRaceCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['event' => $eventId]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('the race results for event :event are computed')]
    public function theRaceResultForEventAreComputed(string $event): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $count = self::cacheDatabase()->fetchOne(
            'SELECT count(id) FROM event_results_by_race WHERE event = :event;',
            ['event' => self::coreDatabase()->fixtureId("motorsport.event.event.{$this->format($event)}")],
        );

        Assert::assertSame(1, $count);
    }

    #[Then('there is a result for :driver in race :race position :position')]
    public function thereIsAResultForDriverInRace(string $driver, int $race, int $position): void
    {
        /** @var string $results */
        $results = self::cacheDatabase()->fetchOne('SELECT results_by_race FROM event_results_by_race LIMIT 1;');

        try {
            /** @var array<array{result: array<array{driver: array{name: string}}>}> $data */
            $data = json_decode($results, true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($data));
        } catch (Throwable $e) {
            Assert::fail($e->getMessage());
        }

        Assert::assertSame($driver, $data[$race - 1]['result'][$position - 1]['driver']['name']);
    }
}
