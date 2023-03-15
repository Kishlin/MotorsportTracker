<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\SearchEventGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchEventRepository extends CoreRepository implements SearchEventGateway
{
    public function find(UuidValueObject $season, StringValueObject $name, PositiveIntValueObject $index): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('e.id')
            ->from('event', 'e')
            ->where('e.season = :season')
            ->withParam('season', $season->value())
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('e.name', ':name'),
                $qb->expr()->eq('e.index', ':index'),
            ))
            ->withParam('name', $name->value())
            ->withParam('index', $index->value())
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
