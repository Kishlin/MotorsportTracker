<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\DriverMoveGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class DriverMoveGatewayUsingDoctrine extends CoreRepository implements DriverMoveGateway
{
    public function save(DriverMove $driverMove): void
    {
        parent::persist($driverMove);
    }
}
