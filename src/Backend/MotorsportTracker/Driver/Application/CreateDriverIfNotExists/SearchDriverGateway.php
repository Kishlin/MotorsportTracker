<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchDriverGateway
{
    public function findBySlug(string $slug): ?UuidValueObject;
}
