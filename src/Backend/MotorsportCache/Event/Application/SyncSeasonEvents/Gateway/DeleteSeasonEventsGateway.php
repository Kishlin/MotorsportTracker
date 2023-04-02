<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\Gateway;

use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

interface DeleteSeasonEventsGateway
{
    public function deleteIfExists(StringValueObject $championship, StrictlyPositiveIntValueObject $year): bool;
}
