<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonViewer;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchSeasonViewerUsingDoctrine extends CoreRepository implements SearchSeasonViewer
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function search(string $championship, int $year): ?SeasonId
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('s.id')
            ->from('seasons', 's')
            ->leftJoin('s', 'championships', 'c', 'c.id = s.championship')
            ->where('s.year = :year')
            ->andWhere("LOWER(REPLACE(CONCAT(c.name, c.slug), ' ' , '')) LIKE LOWER(REPLACE(:championship, ' ', ''))")
            ->setParameter('championship', "%{$championship}%")
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

        return new SeasonId($result[0]['id']);
    }
}
