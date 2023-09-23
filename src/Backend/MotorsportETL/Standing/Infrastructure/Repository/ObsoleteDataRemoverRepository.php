<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Infrastructure\Repository;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ObsoleteDataRemover;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class ObsoleteDataRemoverRepository extends WriteRepository implements CoreRepositoryInterface, ObsoleteDataRemover
{
    private const QUERY = <<<'SQL'
DELETE FROM %s WHERE season = :season;
SQL;

    private const TABLES = [
        'standing_constructor',
        'standing_driver',
        'standing_team',
        'analytics_constructors',
        'analytics_drivers',
        'analytics_teams',
    ];

    public function removeObsoleteStandingsAndAnalytics(SeasonIdentity $season): void
    {
        foreach (self::TABLES as $table) {
            $this->removeObsoleteStandingsAndAnalyticsFromTable($season, $table);
        }
    }

    private function removeObsoleteStandingsAndAnalyticsFromTable(SeasonIdentity $season, string $table): void
    {
        $this->connection->execute(
            SQLQuery::create(
                sprintf(self::QUERY, $table),
                ['season' => $season->id()],
            ),
        );
    }
}
