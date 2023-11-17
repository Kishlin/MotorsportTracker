<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Classification\Application\SyncEventResults;

interface EventIdGateway
{
    public function findEventId(string $series, int $year, string $event): ?string;
}
