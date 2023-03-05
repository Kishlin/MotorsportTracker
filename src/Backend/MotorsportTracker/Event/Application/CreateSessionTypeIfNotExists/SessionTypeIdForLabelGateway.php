<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SessionTypeIdForLabelGateway
{
    public function idForLabel(StringValueObject $label): ?UuidValueObject;
}
