<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\StepTypeCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class StepType extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $label,
    ) {
    }

    public static function create(UuidValueObject $id, StringValueObject $label): self
    {
        $stepType = new self($id, $label);

        $stepType->record(new StepTypeCreatedDomainEvent($id));

        return $stepType;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, StringValueObject $label): self
    {
        return new self($id, $label);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function label(): StringValueObject
    {
        return $this->label;
    }
}
