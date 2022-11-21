<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate\FindRacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class FindRacerGatewayUsingDoctrine extends DoctrineRepository implements FindRacerGateway
{
    /**
     * @throws DoctrineException|Exception|NonUniqueResultException
     */
    public function find(string $driverId, string $championship, string $dateInTimespan): ?Racer
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb
            ->select('r.id as id')
            ->from('racers', 'r')
            ->leftJoin('r', 'cars', 'c', 'r.car = c.id')
            ->leftJoin('c', 'seasons', 's', 'c.season = s.id')
            ->leftJoin('s', 'championships', 'ch', 's.championship = ch.id')
            ->where('r.driver = :driver')
            ->andWhere('r.endDate > :dateTime')
            ->andWhere('r.startDate < :dateTime')
            ->andWhere("REPLACE(LOWER(CONCAT(ch.name, ch.slug)), ' ', '') LIKE LOWER(REPLACE(:championship, ' ', ''))")
            ->setParameter('championship', "%{$championship}%")
            ->setParameter('dateTime', $dateInTimespan)
            ->setParameter('driver', $driverId)
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (1 < count($result)) {
            throw new NonUniqueResultException();
        }

        if (0 === count($result)) {
            return null;
        }

        return $this->entityManager->getRepository(Racer::class)->find($result[0]['id']);
    }
}
