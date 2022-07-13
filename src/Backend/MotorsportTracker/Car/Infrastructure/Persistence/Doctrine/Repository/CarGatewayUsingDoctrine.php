<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\CarGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CarGatewayUsingDoctrine extends DoctrineRepository implements CarGateway
{
    public function save(Car $car): void
    {
        $this->persist($car);
    }
}
