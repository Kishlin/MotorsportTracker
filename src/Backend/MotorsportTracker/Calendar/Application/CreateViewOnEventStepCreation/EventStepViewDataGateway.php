<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface EventStepViewDataGateway
{
    /**
     * @throws EventStepViewDataNotFoundException
     */
    public function find(UuidValueObject $eventStepId): ?EventStepViewData;
}
