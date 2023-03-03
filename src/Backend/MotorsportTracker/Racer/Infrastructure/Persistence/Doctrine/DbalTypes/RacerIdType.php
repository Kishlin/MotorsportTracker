<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class RacerIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return RacerId::class;
    }
}
