<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;

final class ChampionshipProvider
{
    public static function championship(): Championship
    {
        return Championship::instance(
            new ChampionshipId('9af4082a-0de2-4a8d-bd30-ec5cad0b26ed'),
            new ChampionshipName('Formula 1'),
        );
    }
}
