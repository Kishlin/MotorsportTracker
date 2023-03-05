<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\SearchEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchEventSessionRepositoryUsingDoctrine extends CoreRepository implements SearchEventSessionGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function search(string $slug): ?UuidValueObject
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('es.id')
            ->from(EventSession::class, 'es')
            ->where('es.slug = :slug')
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
