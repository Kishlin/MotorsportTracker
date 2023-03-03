<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationChampionshipId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class ChampionshipPresentationChampionshipIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return ChampionshipPresentationChampionshipId::class;
    }
}
