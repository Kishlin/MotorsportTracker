<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsDriversIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists\SearchAnalyticsDriversGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchAnalyticsDriversRepository extends CoreRepository implements SearchAnalyticsDriversGateway
{
    public function find(UuidValueObject $season, UuidValueObject $driver): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('a.id')
            ->from('analytics_drivers', 'a')
            ->where($qb->expr()->eq('a.season', ':season'))
            ->where($qb->expr()->eq('a.driver', ':driver'))
            ->withParam('season', $season->value())
            ->withParam('driver', $driver->value())
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
