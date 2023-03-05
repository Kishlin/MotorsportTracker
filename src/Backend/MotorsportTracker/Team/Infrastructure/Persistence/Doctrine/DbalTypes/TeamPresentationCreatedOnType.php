<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationCreatedOn;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\DateTimeValueObjectType;

final class TeamPresentationCreatedOnType extends DateTimeValueObjectType
{
    protected function mappedClass(): string
    {
        return TeamPresentationCreatedOn::class;
    }
}
