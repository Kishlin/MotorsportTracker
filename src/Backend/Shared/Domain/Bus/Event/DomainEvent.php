<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Event;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

abstract class DomainEvent implements Event
{
    /**
     * @param UuidValueObject $aggregateUuid the Uuid of the object that raised the event
     */
    public function __construct(
        private readonly UuidValueObject $aggregateUuid,
    ) {}

    /**
     * @return UuidValueObject the Uuid of the object that raised the event
     */
    public function aggregateUuid(): UuidValueObject
    {
        return $this->aggregateUuid;
    }
}
