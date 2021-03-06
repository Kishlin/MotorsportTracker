<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;

final class RacerIdType extends AbstractUuidType
{
    protected function mappedClass(): string
    {
        return RacerId::class;
    }
}
