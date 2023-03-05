<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\SearchEventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchEventRepositoryUsingDoctrine extends CoreRepository implements SearchEventGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function find(string $slug): ?UuidValueObject
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('e.id')
            ->from(Event::class, 'e')
            ->where('e.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
        ;

        try {
            /** @var array{id: UuidValueObject} $data */
            $data = $query->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        return $data['id'];
    }
}
