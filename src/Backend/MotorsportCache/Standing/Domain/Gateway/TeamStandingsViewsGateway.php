<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\TeamStandingsView;

interface TeamStandingsViewsGateway
{
    public function save(TeamStandingsView $view): void;

    public function deleteIfExists(string $championship, int $year): void;
}
