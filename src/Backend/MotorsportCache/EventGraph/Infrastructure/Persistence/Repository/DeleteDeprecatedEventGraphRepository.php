<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphType;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\DeleteDeprecatedEventGraphGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class DeleteDeprecatedEventGraphRepository extends CacheRepository implements DeleteDeprecatedEventGraphGateway
{
    private const QUERY = 'DELETE FROM event_graph WHERE event = :event and type = :type;';

    public function deleteForEvent(string $event, EventGraphType $eventGraphType): bool
    {
        $params = ['event' => $event, 'type' => $eventGraphType->value];

        $query  = SQLQuery::create(self::QUERY, $params);
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return array_key_exists(0, $result);
    }
}
