<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway;

interface DeleteEventResultsByRaceIfExistsGateway
{
    public function deleteIfExists(string $eventId): bool;
}
