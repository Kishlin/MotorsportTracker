<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway;

interface DeleteStandingsIfExistsGateway
{
    public function deleteIfExists(string $championship, int $year): bool;
}
