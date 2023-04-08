<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphType;

interface DeleteDeprecatedEventGraphGateway
{
    public function deleteForEvent(string $event, EventGraphType $eventGraphType): bool;
}
