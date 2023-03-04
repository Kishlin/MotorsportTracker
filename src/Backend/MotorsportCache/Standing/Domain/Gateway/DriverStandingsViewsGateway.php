<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView;

interface DriverStandingsViewsGateway
{
    public function save(DriverStandingsView $view): void;

    public function deleteIfExists(string $championship, int $year): void;
}
