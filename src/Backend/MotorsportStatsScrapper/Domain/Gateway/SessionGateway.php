<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionDTO;

interface SessionGateway
{
    public function find(string $championshipName, int $year, string $event, string $sessionType): ?SessionDTO;
}
