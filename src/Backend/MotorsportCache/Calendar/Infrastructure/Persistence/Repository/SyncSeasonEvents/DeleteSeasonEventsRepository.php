<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\DeleteSeasonEventsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class DeleteSeasonEventsRepository extends CacheRepository implements DeleteSeasonEventsGateway
{
    private const DELETE_QUERY = 'DELETE FROM season_events WHERE championship = :championship AND year = :year';

    public function deleteIfExists(StringValueObject $championship, StrictlyPositiveIntValueObject $year): bool
    {
        $query  = SQLQuery::create(self::DELETE_QUERY, ['championship' => $championship->value(), 'year' => $year->value()]);
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return array_key_exists(0, $result);
    }
}
