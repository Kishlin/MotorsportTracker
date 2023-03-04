<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchEventViewer
{
    public function search(string $seasonId, string $keyword): ?UuidValueObject;
}
