<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchChampionshipGateway
{
    public function findBySlug(string $slug): ?UuidValueObject;
}
