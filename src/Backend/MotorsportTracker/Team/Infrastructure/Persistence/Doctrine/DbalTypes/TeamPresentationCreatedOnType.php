<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationCreatedOn;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\DatetimeValueObjectType;

final class TeamPresentationCreatedOnType extends DatetimeValueObjectType
{
    protected function mappedClass(): string
    {
        return TeamPresentationCreatedOn::class;
    }
}
