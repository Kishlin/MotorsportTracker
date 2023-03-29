<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

interface DeleteEventResultsByRaceIfExistsGateway
{
    public function deleteIfExists(string $eventId): bool;
}
