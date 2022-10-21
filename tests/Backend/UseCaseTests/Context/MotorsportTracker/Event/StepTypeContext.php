<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class StepTypeContext extends MotorsportTrackerContext
{
    private const STEP_TYPE_LABEL = 'race';

    private const STEP_TYPE_LABEL_ALT = 'qualification';

    private ?StepTypeId $stepTypeId     = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->stepTypeRepositorySpy()->clear();
    }

    /**
     * @Given /^a step type exists$/
     *
     * @throws Exception
     */
    public function aStepTypeExists(): void
    {
        self::container()->stepTypeRepositorySpy()->add(StepType::create(
            new StepTypeId(self::STEP_TYPE_ID),
            new StepTypeLabel(self::STEP_TYPE_LABEL),
        ));
    }

    /**
     * @Given /^another step type exists$/
     *
     * @throws Exception
     */
    public function anotherStepTypeExists(): void
    {
        self::container()->stepTypeRepositorySpy()->add(StepType::create(
            new StepTypeId(self::STEP_TYPE_ID_ALT),
            new StepTypeLabel(self::STEP_TYPE_LABEL_ALT),
        ));
    }

    /**
     * @When /^a client searches a step type which does not exist$/
     * @When /^a client searches for the existing step type/
     */
    public function createStepTypeIfNotExists(): void
    {
        $this->stepTypeId      = null;
        $this->thrownException = null;

        try {
            /** @var StepTypeId $stepTypeId */
            $stepTypeId = self::container()->commandBus()->execute(
                CreateStepTypeIfNotExistsCommand::fromScalars(self::STEP_TYPE_LABEL),
            );

            $this->stepTypeId = $stepTypeId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the new step type is saved$/
     */
    public function theStepTypeIsSaved(): void
    {
        Assert::assertNotNull($this->stepTypeId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->stepTypeRepositorySpy()->has($this->stepTypeId));
    }

    /**
     * @Then /^the step type was not recreated$/
     */
    public function theNewStepTypeWasNotRecreated(): void
    {
        $this->theStepTypeIsSaved();

        Assert::assertEquals(1, self::container()->stepTypeRepositorySpy()->count());
    }
}
