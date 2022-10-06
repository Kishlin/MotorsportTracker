<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\RacersForDateTimeAndSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\SeasonId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\View\RacerPOPO;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class RacersForDateTimeAndSeasonGatewayUsingDoctrine extends DoctrineRepository implements RacersForDateTimeAndSeasonGateway
{
    /**
     * @throws Exception
     *
     * @return RacerPOPO[]
     */
    public function findRacersForDateTimeAndSeason(DateTimeImmutable $dateTime, SeasonId $seasonId): array
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('r.id as id, CONCAT(d.firstname, \' \', d.name) as driver_name, c.number as car_number')
            ->from('racers', 'r')
            ->leftJoin('r', 'cars', 'c', 'c.id = r.car')
            ->leftJoin('r', 'drivers', 'd', 'd.id = r.driver')
            ->where('r.startDate <= :date')
            ->andWhere('r.endDate >= :date')
            ->andWhere('c.season = :season')
            ->setParameter('season', $seasonId->value())
            ->setParameter('date', $dateTime->format('Y-m-d H:i:s'))
        ;

        /** @var array<array{id: string, driver_name: string, car_number: int}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return array_map([$this, 'resultToRacerPOPO'], $result);
    }

    /**
     * @param array{id: string, driver_name: string, car_number: int} $line
     */
    private static function resultToRacerPOPO(array $line): RacerPOPO
    {
        return RacerPOPO::fromData($line);
    }
}
