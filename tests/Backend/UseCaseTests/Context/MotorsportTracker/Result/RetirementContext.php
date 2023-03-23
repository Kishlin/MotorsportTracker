<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Result;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\CreateRetirementIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class RetirementContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $retirementId = null;
    private ?Throwable $thrownException    = null;

    public function clearGatewaySpies(): void
    {
        self::container()->retirementRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the retirement :name exists')]
    public function theRetirementExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.result.retirement.{$this->format($name)}");
    }

    #[When('a client creates the retirement of car number :number in session :session')]
    #[When('a client creates the retirement for the same number and session')]
    public function aClientCreatesARetirement(
        int $number = 33,
        string $session = 'motorsport.event.eventSession.australianGrandPrix2022Race'
    ): void {
        $this->retirementId    = null;
        $this->thrownException = null;

        try {
            $sessionId = $this->fixtureId("motorsport.event.eventSession.{$this->format($session)}");

            /** @var UuidValueObject $retirementId */
            $retirementId = self::container()->commandBus()->execute(
                CreateRetirementIfNotExistsCommand::fromScalars($sessionId, $number, 'Power Unit', 'Mechanical', false, 38),
            );

            $this->retirementId = $retirementId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the retirement is saved')]
    #[Then('the retirement is not duplicated')]
    public function theRetirementIsSaved(): void
    {
        Assert::assertCount(1, self::container()->retirementRepositorySpy()->all());

        Assert::assertNotNull($this->retirementId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->retirementRepositorySpy()->has($this->retirementId));
    }

    #[Then('the id of the retirement of car number :number in session :session')]
    public function theIdOfTheEntryIsReturned(string $session, int $number): void
    {
        Assert::assertNotNull($this->retirementId);
        Assert::assertNull($this->thrownException);

        $entryId = self::container()->entryRepositorySpy()->findForSessionAndNumber(
            new UuidValueObject(self::fixtureId("motorsport.event.eventSession.{$this->format($session)}")),
            new PositiveIntValueObject($number),
        );

        Assert::assertNotNull($entryId);

        Assert::assertTrue(self::container()->retirementRepositorySpy()->safeGet($this->retirementId)->entry()->equals($entryId));
    }
}
