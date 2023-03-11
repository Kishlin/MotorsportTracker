<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Infrastructure\Persistence\Doctrine\Repository\CreateEntryIfNotExists;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists\SearchEntryGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SearchEntryRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SearchEntryGateway
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function find(UuidValueObject $event, UuidValueObject $driver, StringValueObject $carNumber): ?UuidValueObject
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('e.id')
            ->from('entries', 'e')
            ->where('e.event = :event')
            ->andWhere('e.driver = :driver')
            ->andWhere('e.car_number = :carNumber')
            ->setParameter('event', $event->value())
            ->setParameter('driver', $driver->value())
            ->setParameter('carNumber', $carNumber->value())
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
