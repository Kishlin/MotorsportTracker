<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Gateway;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface FindEntryForSessionAndNumberGateway
{
    public function findForSessionAndNumber(UuidValueObject $session, PositiveIntValueObject $number): ?UuidValueObject;
}
