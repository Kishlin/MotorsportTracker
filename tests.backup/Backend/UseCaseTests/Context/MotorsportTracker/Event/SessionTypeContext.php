<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\CreateSessionTypeIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SessionTypeContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $sessionTypeId = null;
    private ?Throwable $thrownException     = null;

    public function clearGatewaySpies(): void
    {
        self::container()->sessionTypeRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the sessionType :label exists')]
    public function theSessionTypeExists(string $label): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.event.sessionType.{$this->format($label)}");
    }

    #[When('a client searches for the sessionType with label :label')]
    public function aClientSearchesForTheSessionType(string $label): void
    {
        $this->sessionTypeId   = null;
        $this->thrownException = null;

        try {
            /** @var UuidValueObject $sessionTypeId */
            $sessionTypeId = self::container()->commandBus()->execute(
                CreateSessionTypeIfNotExistsCommand::fromScalars($this->format($label)),
            );

            $this->sessionTypeId = $sessionTypeId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the session type is saved')]
    public function theSessionTypeIsSaved(): void
    {
        Assert::assertNotNull($this->sessionTypeId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->sessionTypeRepositorySpy()->has($this->sessionTypeId));
    }

    #[Then('the session type is not recreated')]
    public function theNewSessionTypeWasNotRecreated(): void
    {
        $this->theSessionTypeIsSaved();

        Assert::assertEquals(1, self::container()->sessionTypeRepositorySpy()->count());
    }
}
