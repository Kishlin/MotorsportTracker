<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\ExistingRacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class ExistingRacerGatewayUsingDoctrine extends DoctrineRepository implements ExistingRacerGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function findIfExistsForDriverMove(UuidValueObject $driverMoveId): ?Racer
    {
        $qb = $this->entityManager->getRepository(Racer::class)->createQueryBuilder('racer');

        $qb
            ->leftJoin(DriverMove::class, 'driverMove', Expr\Join::WITH, 'racer.driverId = driverMove.driverId')
            ->where('driverMove.id = :driverMoveId')
            ->andWhere('racer.startDate <= driverMove.date')
            ->andWhere('racer.endDate >= driverMove.date')
            ->setParameter('driverMoveId', $driverMoveId->value())
        ;

        $result = $qb->getQuery()->getOneOrNullResult();

        assert(null === $result || $result instanceof Racer);

        return $result;
    }
}
