<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationTeamId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class TeamPresentationTeamIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return TeamPresentationTeamId::class;
    }
}
