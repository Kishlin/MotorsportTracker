<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface FindSeasonGateway
{
    public function find(string $championshipId, int $year): ?UuidValueObject;
}
