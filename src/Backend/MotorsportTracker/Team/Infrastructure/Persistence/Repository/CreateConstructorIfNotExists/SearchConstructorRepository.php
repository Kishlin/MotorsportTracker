<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateConstructorIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorIfNotExists\SearchConstructorGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchConstructorRepository extends CoreRepository implements SearchConstructorGateway
{
    public function findForNameAndRef(StringValueObject $name, NullableUuidValueObject $ref): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('c.id')
            ->from('constructor', 'c')
            ->where($qb->expr()->eq('c.ref', ':ref'))
            ->andWhere($qb->expr()->eq('c.name', ':name'))
            ->withParam('name', $name->value())
            ->withParam('ref', $ref->value())
        ;

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
