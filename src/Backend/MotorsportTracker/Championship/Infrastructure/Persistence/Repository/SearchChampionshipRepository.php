<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SearchChampionshipGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchChampionshipRepository extends CoreRepository implements SearchChampionshipGateway
{
    public function findIfExists(StringValueObject $championship, NullableUuidValueObject $ref): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('c.id')
            ->from('championship', 'c')
            ->where($qb->expr()->eq('c.name', ':name'))
            ->withParam('name', $championship->value())
        ;

        if (null !== $ref->value()) {
            $qb->andWhere($qb->expr()->eq('c.ref', ':ref'))->withParam('ref', $ref->value());
        }

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
