<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchEventSessionGateway
{
    public function search(
        UuidValueObject $event,
        UuidValueObject $typeId,
        NullableDateTimeValueObject $startDate,
    ): ?ExistingEventSessionDTO;
}
