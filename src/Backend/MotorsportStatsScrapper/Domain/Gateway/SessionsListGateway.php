<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionsListDTO;

interface SessionsListGateway
{
    public function allSessionsForEvent(string $championship, int $year, string $event): SessionsListDTO;
}
