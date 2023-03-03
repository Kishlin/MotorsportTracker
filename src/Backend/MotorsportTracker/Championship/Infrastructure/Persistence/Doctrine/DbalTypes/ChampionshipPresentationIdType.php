<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class ChampionshipPresentationIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return ChampionshipPresentationId::class;
    }
}
