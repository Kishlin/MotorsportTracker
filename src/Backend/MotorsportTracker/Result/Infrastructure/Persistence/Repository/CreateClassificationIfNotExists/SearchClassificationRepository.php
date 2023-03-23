<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\SearchClassificationGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchClassificationRepository extends CoreRepository implements SearchClassificationGateway
{
    public function findForEntry(UuidValueObject $entry): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('c.id')
            ->from('classification', 'c')
            ->where($qb->expr()->eq('c.entry', ':entry'))
            ->withParam('entry', $entry->value())
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
