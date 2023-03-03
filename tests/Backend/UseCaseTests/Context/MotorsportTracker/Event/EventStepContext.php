<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\CreateEventStepCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepAtTheSameTimeException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepWithTypeException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventStepContext extends MotorsportTrackerContext
{
    private ?EventStepId $eventStepId   = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventStepRepositorySpy()->clear();
    }

    /**
     * @Given the eventStep :name exists
     *
     * @throws Exception
     */
    public function theEventStepExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.event.eventStep.{$this->format($name)}");
    }

    /**
     * @When a client creates the :stepType step for the event :event at :dateTime
     */
    public function aClientCreatesAnEventStepForTheEventAndStepType(string $stepType, string $event, string $dateTime): void
    {
        $this->eventStepId     = null;
        $this->thrownException = null;

        try {
            $stepTypeId = $this->fixtureId("motorsport.event.stepType.{$this->format($stepType)}");
            $eventId    = $this->fixtureId("motorsport.event.event.{$this->format($event)}");

            /** @var EventStepId $eventStepId */
            $eventStepId = self::container()->commandBus()->execute(
                CreateEventStepCommand::fromScalars($eventId, $stepTypeId, $dateTime),
            );

            $this->eventStepId = $eventStepId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the event step is saved$/
     */
    public function theEventStepIsSaved(): void
    {
        Assert::assertNotNull($this->eventStepId);
        Assert::assertTrue(self::container()->eventStepRepositorySpy()->has($this->eventStepId));
    }

    /**
     * @Then /^the event step creation for the same type is declined$/
     */
    public function theEventStepCreationForTheSameTypeIsDeclined(): void
    {
        Assert::assertNull($this->eventStepId);
        Assert::assertInstanceOf(EventHasStepWithTypeException::class, $this->thrownException);
    }

    /**
     * @Then /^the event step creation for the same time is declined$/
     */
    public function theEventStepCreationForTheSameTimeIsDeclined(): void
    {
        Assert::assertNull($this->eventStepId);
        Assert::assertInstanceOf(EventHasStepAtTheSameTimeException::class, $this->thrownException);
    }
}
