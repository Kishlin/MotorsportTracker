<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\DriverStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class DriverStandingRepositoryUsingDoctrine extends DoctrineRepository implements DriverStandingGateway
{
    public function save(DriverStanding $driverStanding): void
    {
        $this->persist($driverStanding);
    }

    public function find(DriverStandingDriverId $driverId, DriverStandingEventId $eventId): ?DriverStanding
    {
        return $this->entityManager->getRepository(DriverStanding::class)->findOneBy([
            'driverId' => $driverId,
            'eventId'  => $eventId,
        ]);
    }
}
