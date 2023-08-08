<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway;

interface DeleteEventResultsBySessionsIfExistsGateway
{
    public function deleteIfExists(string $eventId): bool;
}
