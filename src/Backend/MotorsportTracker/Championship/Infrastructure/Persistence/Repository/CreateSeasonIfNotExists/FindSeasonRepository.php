<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\FindSeasonGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class FindSeasonRepository extends CoreRepository implements FindSeasonGateway
{
    public function find(string $championshipId, int $year): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('s.id')
            ->from('season', 's')
            ->where($qb->expr()->eq('s.year', ':year'))
            ->andWhere($qb->expr()->eq('s.series', ':championship'))
            ->withParam('championship', $championshipId)
            ->withParam('year', $year)
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
