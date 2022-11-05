<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults;

use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ResultsRecordedDomainEvent extends DomainEvent
{
    public function eventStepId(): UuidValueObject
    {
        return $this->aggregateUuid();
    }
}
