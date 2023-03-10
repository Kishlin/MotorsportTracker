<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchTeamGateway
{
    public function findBySlug(string $slug): ?UuidValueObject;
}
