<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface DriverMoveDataGateway
{
    /**
     * @throws DriverMoveDataNotFoundException
     */
    public function find(UuidValueObject $driverMoveId): DriverMoveData;
}
