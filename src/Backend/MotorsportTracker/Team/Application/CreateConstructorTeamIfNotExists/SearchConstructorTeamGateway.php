<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchConstructorTeamGateway
{
    public function find(UuidValueObject $constructor, UuidValueObject $team): ?UuidValueObject;
}
