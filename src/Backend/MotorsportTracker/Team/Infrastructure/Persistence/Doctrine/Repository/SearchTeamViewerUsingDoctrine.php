<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamViewer;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchTeamViewerUsingDoctrine extends CoreRepository implements SearchTeamViewer
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function search(string $keyword): ?TeamId
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('t.id')
            ->from('teams', 't')
            ->where("LOWER(REPLACE(t.name, ' ', '')) LIKE LOWER(REPLACE(:name, ' ', ''))")
            ->setParameter('name', "%{$keyword}%")
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new NonUniqueResultException();
        }

        return new TeamId($result[0]['id']);
    }
}
