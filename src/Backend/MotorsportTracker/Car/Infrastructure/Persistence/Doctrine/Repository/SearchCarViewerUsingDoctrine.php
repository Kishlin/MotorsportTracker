<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarViewer;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchCarViewerUsingDoctrine extends CoreRepository implements SearchCarViewer
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function search(int $number, string $team, string $championship, int $year): ?CarId
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('c.id')
            ->from('cars', 'c')
            ->leftJoin('c', 'teams', 't', 'c.team = t.id')
            ->leftJoin('c', 'seasons', 's', 'c.season = s.id')
            ->leftJoin('c', 'championships', 'ch', 'ch.id = s.championship')
            ->where("LOWER(REPLACE(CONCAT(ch.name, ch.slug), ' ', '')) LIKE LOWER(REPLACE(:championship, ' ', ''))")
            ->andWhere("LOWER(REPLACE(t.name, ' ', '')) LIKE LOWER(REPLACE(:team, ' ', ''))")
            ->andWhere('c.number = :number')
            ->andWhere('s.year = :year')
            ->setParameter('championship', "%{$championship}%")
            ->setParameter('team', "%{$team}%")
            ->setParameter('number', $number)
            ->setParameter('year', $year)
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new NonUniqueResultException();
        }

        return new CarId($result[0]['id']);
    }
}
