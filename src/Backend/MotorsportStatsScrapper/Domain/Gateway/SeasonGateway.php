<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SeasonDTO;

interface SeasonGateway
{
    public function find(string $championshipName, int $year): ?SeasonDTO;
}
