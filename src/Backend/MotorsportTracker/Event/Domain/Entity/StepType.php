<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\StepTypeCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class StepType extends AggregateRoot
{
    private function __construct(
        private StepTypeId $id,
        private StepTypeLabel $label,
    ) {
    }

    public static function create(StepTypeId $id, StepTypeLabel $label): self
    {
        $stepType = new self($id, $label);

        $stepType->record(new StepTypeCreatedDomainEvent($id));

        return $stepType;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(StepTypeId $id, StepTypeLabel $label): self
    {
        return new self($id, $label);
    }

    public function id(): StepTypeId
    {
        return $this->id;
    }

    public function label(): StepTypeLabel
    {
        return $this->label;
    }
}
