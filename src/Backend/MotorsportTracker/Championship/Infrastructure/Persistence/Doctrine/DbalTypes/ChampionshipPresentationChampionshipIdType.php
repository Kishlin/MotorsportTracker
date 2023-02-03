<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationChampionshipId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;

final class ChampionshipPresentationChampionshipIdType extends AbstractUuidType
{
    protected function mappedClass(): string
    {
        return ChampionshipPresentationChampionshipId::class;
    }
}
