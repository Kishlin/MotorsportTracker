<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class SeasonChampionshipIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return SeasonChampionshipId::class;
    }
}
