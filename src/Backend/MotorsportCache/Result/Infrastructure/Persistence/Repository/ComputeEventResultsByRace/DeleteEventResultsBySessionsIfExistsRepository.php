<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\DeleteEventResultsBySessionsIfExistsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class DeleteEventResultsBySessionsIfExistsRepository extends CacheRepository implements DeleteEventResultsBySessionsIfExistsGateway
{
    public function deleteIfExists(string $eventId): bool
    {
        $query  = SQLQuery::create('DELETE FROM event_results_by_race WHERE event = :event', ['event' => $eventId]);
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return array_key_exists(0, $result);
    }
}
