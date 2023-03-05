<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\SaveStepTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\StepTypeIdForLabelGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property StepType[] $objects
 *
 * @method StepType[]    all()
 * @method null|StepType get(UuidValueObject $id)
 * @method StepType      safeGet(UuidValueObject $id)
 */
final class SaveStepTypeRepositorySpy extends AbstractRepositorySpy implements SaveStepTypeGateway, StepTypeIdForLabelGateway
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

    public function idForLabel(StringValueObject $label): ?UuidValueObject
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
