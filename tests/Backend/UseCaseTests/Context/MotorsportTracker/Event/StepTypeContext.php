<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class StepTypeContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $stepTypeId = null;
    private ?Throwable $thrownException  = null;

    public function clearGatewaySpies(): void
    {
        self::container()->stepTypeRepositorySpy()->clear();
    }

    /**
     * @Given the stepType :label exists
     *
     * @throws Exception
     */
    public function theStepTypeExists(string $label): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.event.stepType.{$this->format($label)}");
    }

    /**
     * @When a client searches for the stepType with label :label
     */
    public function aClientSearchesForTheStepType(string $label): void
    {
        $this->stepTypeId      = null;
        $this->thrownException = null;

        try {
            /** @var UuidValueObject $stepTypeId */
            $stepTypeId = self::container()->commandBus()->execute(
                CreateStepTypeIfNotExistsCommand::fromScalars($this->format($label)),
            );

            $this->stepTypeId = $stepTypeId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the step type is saved$/
     */
    public function theStepTypeIsSaved(): void
    {
        Assert::assertNotNull($this->stepTypeId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->stepTypeRepositorySpy()->has($this->stepTypeId));
    }

    /**
     * @Then /^the step type is not recreated$/
     */
    public function theNewStepTypeWasNotRecreated(): void
    {
        $this->theStepTypeIsSaved();

        Assert::assertEquals(1, self::container()->stepTypeRepositorySpy()->count());
    }
}
