<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchDriverViewer
{
    public function search(string $name): ?UuidValueObject;
}
