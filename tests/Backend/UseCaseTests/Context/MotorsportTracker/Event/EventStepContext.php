<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\CreateEventStepCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepAtTheSameTimeException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepWithTypeException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventStepContext extends MotorsportTrackerContext
{
    private const EVENT_STEP_DATE_TIME     = '1993-11-22 18:00:00';
    private const EVENT_STEP_DATE_TIME_ALT = '1993-11-22 22:00:00';

    private ?EventStepId $eventStepId   = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventStepRepositorySpy()->clear();
    }

    /**
     * @Given /^an event step exists for the event and step type$/
     */
    public function anEventStepExistsForTheEventAndStepType(): void
    {
        self::container()->eventStepRepositorySpy()->add(EventStep::instance(
            new EventStepId(self::EVENT_STEP_ID),
            new EventStepTypeId(self::STEP_TYPE_ID),
            new EventStepEventId(self::EVENT_ID),
            new EventStepDateTime(new DateTimeImmutable(self::EVENT_STEP_DATE_TIME)),
        ));
    }

    /**
     * @When /^a client creates a new event step for the event and step type$/
     * @When /^a client creates a new event step for the same event and (same|other) step type and (same|other) time$/
     */
    public function aClientCreatesAnEventStepForTheEventAndStepType(string $stepType = 'same', string $time = 'same'): void
    {
        $this->eventStepId     = null;
        $this->thrownException = null;

        try {
            /** @var EventStepId $eventStepId */
            $eventStepId = self::container()->commandBus()->execute(
                CreateEventStepCommand::fromScalars(
                    self::EVENT_ID,
                    'other' === $stepType ? self::STEP_TYPE_ID_ALT : self::STEP_TYPE_ID,
                    'other' === $time ? self::EVENT_STEP_DATE_TIME_ALT : self::EVENT_STEP_DATE_TIME,
                ),
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
