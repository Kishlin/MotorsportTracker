<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series;

interface SaveChampionshipGateway
{
    public function save(Series $championship): void;
}
