<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface ExistingRacerGateway
{
    public function findIfExistsForDriverMove(UuidValueObject $driverMoveId): ?Racer;
}
