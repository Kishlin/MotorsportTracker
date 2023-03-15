<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Repository\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\SearchDriverGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchDriverRepository extends CoreRepository implements SearchDriverGateway
{
    public function findByNameOrRef(StringValueObject $name, NullableUuidValueObject $ref): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('d.id')
            ->from('driver', 'd')
            ->where($qb->expr()->eq('d.name', ':name'))
            ->withParam('name', $name->value())
        ;

        if (null !== $ref->value()) {
            $qb->andWhere($qb->expr()->eq('d.ref', ':ref'))->withParam('ref', $ref->value());
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
