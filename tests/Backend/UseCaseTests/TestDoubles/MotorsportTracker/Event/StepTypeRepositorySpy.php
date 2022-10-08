<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\StepTypeIdForLabelGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\StepTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property StepType[] $objects
 *
 * @method StepType get(StepTypeId $id)
 */
final class StepTypeRepositorySpy extends AbstractRepositorySpy implements StepTypeGateway, StepTypeIdForLabelGateway
{
    /**
     * @throws Exception
     */
    public function save(StepType $stepType): void
    {
        if ($this->labelAlreadyTaken($stepType)) {
            throw new Exception();
        }

        $this->objects[$stepType->id()->value()] = $stepType;
    }


    public function idForLabel(StepTypeLabel $label): ?StepTypeId
    {
        foreach ($this->objects as $savedEventStep) {
            if ($savedEventStep->label()->equals($label)) {
                return $savedEventStep->id();
            }
        }

        return null;
    }

    private function labelAlreadyTaken(StepType $stepType): bool
    {
        foreach ($this->objects as $savedStepType) {
            if ($savedStepType->label()->equals($stepType->label())) {
                return true;
            }
        }

        return false;
    }
}
