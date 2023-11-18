<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;

interface EntryGateway
{
    public function find(SessionIdentity $sessionIdentity, string $carNumber): ?string;
}
