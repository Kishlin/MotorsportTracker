<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\CreateOrUpdateEventSessionCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\EventSessionCreationFailureException;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventSessionContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $eventSessionId = null;
    private ?Throwable $thrownException      = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventSessionRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the eventSession :name exists')]
    public function theEventSessionExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.event.eventSession.{$this->format($name)}");
    }

    #[When('a client creates the :sessionType session for the event :event at :dateTime with slug :slug')]
    public function aClientCreatesAnEventSessionForTheEventAndStepType(string $sessionType, string $event, string $dateTime, string $slug): void
    {
        $this->eventSessionId  = null;
        $this->thrownException = null;

        try {
            $stepTypeId = $this->fixtureId("motorsport.event.sessionType.{$this->format($sessionType)}");
            $eventId    = $this->fixtureId("motorsport.event.event.{$this->format($event)}");

            /** @var UuidValueObject $eventSessionId */
            $eventSessionId = self::container()->commandBus()->execute(
                CreateOrUpdateEventSessionCommand::fromScalars(
                    $eventId,
                    $stepTypeId,
                    false,
                    new DateTimeImmutable($dateTime),
                    new DateTimeImmutable($dateTime),
                    null,
                ),
            );

            $this->eventSessionId = $eventSessionId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the event session is saved')]
    #[Then('the id of the event session is returned')]
    public function theEventSessionIsSaved(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->eventSessionId);
        Assert::assertTrue(self::container()->eventSessionRepositorySpy()->has($this->eventSessionId));
    }

    #[Then('the event session creation for the same type is declined')]
    public function theEventSessionCreationForTheSameTypeIsDeclined(): void
    {
        Assert::assertNull($this->eventSessionId);
        Assert::assertInstanceOf(EventSessionCreationFailureException::class, $this->thrownException);
    }
}
