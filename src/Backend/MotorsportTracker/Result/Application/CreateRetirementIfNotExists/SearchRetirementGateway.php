<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchRetirementGateway
{
    public function findForEntry(UuidValueObject $entry): ?UuidValueObject;
}
