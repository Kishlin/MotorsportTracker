<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface UpdateEventSessionGateway
{
    public function update(
        UuidValueObject $sessionId,
        NullableDateTimeValueObject $endDate,
        BoolValueObject $hasResult,
    ): bool;
}
