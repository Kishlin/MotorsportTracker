<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Mutator\Championship;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity\Championship;

interface ChampionshipMutator
{
    public function apply(Championship $championship): void;
}