<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class DriverGatewayUsingDoctrine extends CoreRepository implements DriverGateway
{
    public function save(Driver $driver): void
    {
        parent::persist($driver);
    }
}
