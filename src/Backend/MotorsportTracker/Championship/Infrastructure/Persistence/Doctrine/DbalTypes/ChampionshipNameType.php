<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class ChampionshipNameType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return ChampionshipName::class;
    }
}
