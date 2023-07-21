<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\ComputeSeasonStandings;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway\DeleteStandingsIfExistsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class DeleteStandingsRepository extends CacheRepository implements DeleteStandingsIfExistsGateway
{
    public function deleteIfExists(string $championship, int $year): bool
    {
        $params = ['ch' => $championship, 'year' => $year];
        $query  = SQLQuery::create('DELETE FROM standings WHERE championship = :ch AND year = :year', $params);

        $result = $this->connection->execute($query)->fetchAllAssociative();

        return array_key_exists(0, $result);
    }
}
