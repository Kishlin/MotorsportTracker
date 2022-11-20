<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventViewer;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class SearchEventViewerUsingDoctrine extends DoctrineRepository implements SearchEventViewer
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function search(string $seasonId, string $keyword): ?EventId
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('e.id as id')
            ->from('events', 'e')
            ->leftJoin('e', 'venues', 'v', 'e.venue = v.id')
            ->where("LOWER(REPLACE(CONCAT(e.label, v.name), ' ', '')) LIKE LOWER(REPLACE(:keyword, ' ', ''))")
            ->andWhere('e.season = :seasonId')
            ->setParameter('keyword', "%{$keyword}%")
            ->setParameter('seasonId', $seasonId)
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new NonUniqueResultException();
        }

        return new EventId($result[0]['id']);
    }
}
