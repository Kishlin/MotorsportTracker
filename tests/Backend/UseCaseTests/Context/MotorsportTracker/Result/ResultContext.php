<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Result;

use Behat\Gherkin\Node\TableNode;
use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\RecordResultsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\ResultDTO;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ResultContext extends MotorsportTrackerContext
{
    private ?Throwable $thrownException = null;

    private int $recordedCount = 0;

    /**
     * @AfterScenario
     */
    public function clearCounter(): void
    {
        $this->recordedCount = 0;
    }

    public function clearGatewaySpies(): void
    {
        self::container()->resultRepositorySpy()->clear();
    }

    /**
     * @When a client records the following results for eventStep :eventStep:
     */
    public function aClientCreatesADriver(string $eventStep, TableNode $results): void
    {
        $this->thrownException = null;

        try {
            $eventStepId = $this->fixtureId("motorsport.event.eventStep.{$this->format($eventStep)}");
            $resultsDTOs = [];

            /** @var array<array{racer: string, fastestLapTime: string, position: int, points: float}> $resultTable */
            $resultTable = $results;

            foreach ($resultTable as $result) {
                $resultsDTOs[] = ResultDTO::fromScalars(
                    $this->fixtureId("motorsport.racer.racer.{$this->format($result['racer'])}"),
                    $result['fastestLapTime'],
                    (int) $result['position'],
                    (float) $result['points'],
                );

                ++$this->recordedCount;
            }

            self::container()->commandBus()->execute(
                RecordResultsCommand::fromScalars($eventStepId, $resultsDTOs),
            );
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the results are recorded$/
     */
    public function theResultsAreRecorded(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertSame($this->recordedCount, self::container()->resultRepositorySpy()->count());
    }

    /**
     * @Then /^the results are not recorded$/
     */
    public function theDriverCreationIsDeclined(): void
    {
        Assert::assertNotNull($this->thrownException);
        Assert::assertNotSame($this->recordedCount, self::container()->resultRepositorySpy()->count());
    }
}
