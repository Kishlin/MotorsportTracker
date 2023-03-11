<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\CreateSeasonIfNotExists;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\FindSeasonGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class FindSeasonRepositoryUsingDoctrine extends CoreRepositoryLegacy implements FindSeasonGateway
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function find(string $championshipId, int $year): ?UuidValueObject
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('s.id')
            ->from('seasons', 's')
            ->where('s.year = :year')
            ->andWhere('s.championship = :championship')
            ->setParameter('championship', $championshipId)
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

        return new UuidValueObject($result[0]['id']);
    }
}
