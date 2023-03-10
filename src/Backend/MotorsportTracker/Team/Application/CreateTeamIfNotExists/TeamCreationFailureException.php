<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class TeamCreationFailureException extends DomainException
{
}
