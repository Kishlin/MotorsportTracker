<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateAnalyticsTeamsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists\SearchAnalyticsTeamsGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchAnalyticsTeamsRepository extends CoreRepository implements SearchAnalyticsTeamsGateway
{
    public function find(UuidValueObject $season, UuidValueObject $team): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('a.id')
            ->from('analytics_teams', 'a')
            ->where($qb->expr()->eq('a.season', ':season'))
            ->where($qb->expr()->eq('a.team', ':team'))
            ->withParam('season', $season->value())
            ->withParam('team', $team->value())
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
