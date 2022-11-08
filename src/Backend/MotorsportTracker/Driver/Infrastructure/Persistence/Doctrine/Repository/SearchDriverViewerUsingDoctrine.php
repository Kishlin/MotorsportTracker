<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverViewer;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class SearchDriverViewerUsingDoctrine extends DoctrineRepository implements SearchDriverViewer
{
    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function search(string $name): ?DriverId
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('d.id')
            ->from('drivers', 'd')
            ->where("LOWER(REPLACE(CONCAT(d.firstname, d.name), ' ', '')) LIKE LOWER(REPLACE(:name, ' ', ''))")
            ->setParameter('name', "%{$name}%")
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new NonUniqueResultException();
        }

        return new DriverId($result[0]['id']);
    }
}
