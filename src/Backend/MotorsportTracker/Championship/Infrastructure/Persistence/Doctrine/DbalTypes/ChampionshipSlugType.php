<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipSlug;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class ChampionshipSlugType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return ChampionshipSlug::class;
    }
}
