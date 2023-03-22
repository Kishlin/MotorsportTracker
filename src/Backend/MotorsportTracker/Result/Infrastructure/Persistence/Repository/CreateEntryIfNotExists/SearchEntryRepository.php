<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\SearchEntryGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchEntryRepository extends CoreRepository implements SearchEntryGateway
{
    public function find(
        UuidValueObject $session,
        UuidValueObject $driver,
        UuidValueObject $team,
        PositiveIntValueObject $carNumber,
    ): ?UuidValueObject {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('e.id')
            ->from('entry', 'e')
            ->where($qb->expr()->eq('e.session', ':session'))
            ->andWhere($qb->expr()->eq('e.driver', ':driver'))
            ->andWhere($qb->expr()->eq('e.team', ':team'))
            ->andWhere($qb->expr()->eq('e.car_number', ':carNumber'))
            ->withParam('carNumber', $carNumber->value())
            ->withParam('session', $session->value())
            ->withParam('driver', $driver->value())
            ->withParam('team', $team->value())
            ->buildQuery()
        ;

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
