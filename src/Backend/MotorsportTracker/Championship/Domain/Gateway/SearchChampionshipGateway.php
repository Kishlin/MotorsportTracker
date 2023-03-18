<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway;

use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchChampionshipGateway
{
    public function findIfExists(StringValueObject $championship, NullableUuidValueObject $ref): ?UuidValueObject;
}
