<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;

interface SaveSeasonGateway
{
    public function save(Season $season): void;
}
