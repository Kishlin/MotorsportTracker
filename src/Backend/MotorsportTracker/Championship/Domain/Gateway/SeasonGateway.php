<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;

interface SeasonGateway
{
    public function save(Season $season): void;
}
