<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\SearchRetirementGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchRetirementRepository extends CoreRepository implements SearchRetirementGateway
{
    public function findForEntry(UuidValueObject $entry): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('r.id')
            ->from('retirement', 'r')
            ->where($qb->expr()->eq('r.entry', ':entry'))
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
