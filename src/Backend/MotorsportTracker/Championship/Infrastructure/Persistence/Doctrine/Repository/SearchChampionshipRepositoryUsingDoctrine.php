<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SearchChampionshipGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchChampionshipRepositoryUsingDoctrine extends CoreRepository implements SearchChampionshipGateway
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function findIfExists(StringValueObject $shortCode, NullableUuidValueObject $ref): ?UuidValueObject
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('c.id')
            ->from('championships', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $shortCode->value())
        ;

        if (null !== $ref->value()) {
            $qb->andWhere('c.ref = :ref')->setParameter('ref', $ref->value());
        }

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
