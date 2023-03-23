<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\SearchRaceLapGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchRaceLapRepository extends CoreRepository implements SearchRaceLapGateway
{
    public function findForEntryAndLap(UuidValueObject $entry, PositiveIntValueObject $lap): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('rl.id')
            ->from('race_lap', 'rl')
            ->where($qb->expr()->eq('rl.lap', ':lap'))
            ->andWhere($qb->expr()->eq('rl.entry', ':entry'))
            ->withParam('entry', $entry->value())
            ->withParam('lap', $lap->value())
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
