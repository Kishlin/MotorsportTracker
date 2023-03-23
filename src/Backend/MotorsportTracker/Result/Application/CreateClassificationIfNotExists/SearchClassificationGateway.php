<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchClassificationGateway
{
    public function findForEntry(UuidValueObject $entry): ?UuidValueObject;
}
