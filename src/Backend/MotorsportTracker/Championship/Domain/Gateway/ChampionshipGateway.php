<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;

interface ChampionshipGateway
{
    public function save(Championship $championship): void;
}
