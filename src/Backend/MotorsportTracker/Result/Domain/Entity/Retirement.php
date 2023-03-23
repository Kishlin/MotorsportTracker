<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\RetirementCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Retirement extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $entry,
        private readonly StringValueObject $reason,
        private readonly StringValueObject $type,
        private readonly BoolValueObject $dns,
        private readonly PositiveIntValueObject $lap,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $entry,
        StringValueObject $reason,
        StringValueObject $type,
        BoolValueObject $dns,
        PositiveIntValueObject $lap,
    ): self {
        $retirement = new self($id, $entry, $reason, $type, $dns, $lap);

        $retirement->record(new RetirementCreatedDomainEvent($id));

        return $retirement;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $entry,
        StringValueObject $reason,
        StringValueObject $type,
        BoolValueObject $dns,
        PositiveIntValueObject $lap,
    ): self {
        return new self($id, $entry, $reason, $type, $dns, $lap);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function entry(): UuidValueObject
    {
        return $this->entry;
    }

    public function reason(): StringValueObject
    {
        return $this->reason;
    }

    public function type(): StringValueObject
    {
        return $this->type;
    }

    public function dns(): BoolValueObject
    {
        return $this->dns;
    }

    public function lap(): PositiveIntValueObject
    {
        return $this->lap;
    }

    public function mappedData(): array
    {
        return [
            'id'     => $this->id->value(),
            'entry'  => $this->entry->value(),
            'reason' => $this->reason->value(),
            'type'   => $this->type->value(),
            'dns'    => $this->dns->value() ? 1 : 0,
            'lap'    => $this->lap->value(),
        ];
    }
}
