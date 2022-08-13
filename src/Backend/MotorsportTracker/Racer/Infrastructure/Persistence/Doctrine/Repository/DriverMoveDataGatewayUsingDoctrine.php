<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception as DoctrineException;
use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveData;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveDataGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class DriverMoveDataGatewayUsingDoctrine extends DoctrineRepository implements DriverMoveDataGateway
{
    /**
     * @throws DoctrineException|Exception
     */
    public function find(UuidValueObject $driverMoveId): DriverMoveData
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('dm.driver, dm.car, dm.date')
            ->from('driver_moves', 'dm')
            ->where('dm.id = :id')
            ->setParameter('id', $driverMoveId->value())
        ;

        /** @var array{driver: string, car: string, date: string} $result */
        $result = $qb->executeQuery()->fetchAssociative();

        return DriverMoveData::fromData($result);
    }
}
