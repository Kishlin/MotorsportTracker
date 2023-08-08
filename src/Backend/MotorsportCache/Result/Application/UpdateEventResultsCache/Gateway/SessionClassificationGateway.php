<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\DTO\SessionResultDTO;

interface SessionClassificationGateway
{
    public function findResult(string $eventSessionId): SessionResultDTO;
}
