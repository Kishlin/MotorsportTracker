<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class StandingCreatedDomainEvent extends DomainEvent
{
    /**
     * @param UuidValueObject $aggregateUuid the Uuid of the object that raised the event
     */
    public function __construct(
        private readonly UuidValueObject $aggregateUuid,
        private readonly StandingType $standingType,
    ) {
        parent::__construct($this->aggregateUuid);
    }

    public function standingType(): StandingType
    {
        return $this->standingType;
    }
}
