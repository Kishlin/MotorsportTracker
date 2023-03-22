<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\DriverByNameGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class DriverByNameRepository extends CoreRepository implements DriverByNameGateway
{
    public function find(StringValueObject $name): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('d.id')
            ->from('driver', 'd')
            ->where($qb->expr()->eq('d.name', ':name'))
            ->withParam('name', $name->value())
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
