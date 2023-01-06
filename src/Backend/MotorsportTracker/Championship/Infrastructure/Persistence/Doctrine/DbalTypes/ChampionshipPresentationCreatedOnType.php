<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationCreatedOn;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractDatetimeType;

final class ChampionshipPresentationCreatedOnType extends AbstractDatetimeType
{
    protected function mappedClass(): string
    {
        return ChampionshipPresentationCreatedOn::class;
    }
}
