<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateChampionshipPresentation;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\SearchSeriesGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchSeriesRepository extends CoreRepository implements SearchSeriesGateway
{
    public function findIfExists(StringValueObject $series, NullableUuidValueObject $ref): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('c.id')
            ->from('series', 'c')
        ;

        if (null !== $ref->value()) {
            $qb->andWhere($qb->expr()->eq('c.ref', ':ref'))->withParam('ref', $ref->value());
        } else {
            $qb->andWhere($qb->expr()->eq('c.name', ':name'))->withParam('name', $series->value());
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
